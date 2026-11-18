'use client';

import {useId, useState} from 'react';
import './color-section.css';

type ColorPair = readonly [light: string, dark: string];
type Swatch = {token: string; label: string; colors: ColorPair; ink?: ColorPair; outline?: boolean};

const white = 'oklch(100% 0 0)';
const black = 'oklch(0% 0 0)';
const snow = 'oklch(0.9911 0 0)';
const eclipse = 'oklch(0.2103 0.0059 285.89)';
const accent = 'oklch(0.6204 0.195 253.83)';
const success = 'oklch(0.7329 0.1935 150.81)';
const background: ColorPair = ['oklch(0.9702 0 0)', 'oklch(12% 0.005 285.823)'];
const foreground: ColorPair = [eclipse, snow];
const surface: ColorPair = [white, eclipse];
const muted: ColorPair = ['oklch(0.5517 0.0138 285.94)', 'oklch(70.5% 0.015 286.067)'];
const segment: ColorPair = [white, 'oklch(0.3964 0.01 285.93)'];
const border: ColorPair = ['oklch(90% 0.004 286.32)', 'oklch(28% 0.006 286.033)'];

function mix(base: ColorPair, weight: number, withColor: ColorPair): ColorPair {
  return [
    `color-mix(in oklab, ${base[0]} ${weight}%, ${withColor[0]})`,
    `color-mix(in oklab, ${base[1]} ${weight}%, ${withColor[1]})`,
  ];
}

function role(token: string, base: ColorPair, ink: ColorPair, soft = true) {
  const solid = {
    base: {token, label: 'Base', colors: base},
    hover: {token: `${token}-hover`, label: 'Hover', colors: mix(base, soft ? 90 : 96, ink)},
    foreground: {token: `${token}-foreground`, label: 'Foreground', colors: ink},
  };

  return {
    solid,
    soft: soft ? {
      base: {token: `${token}-soft`, label: 'Soft', colors: mix(base, 15, ['transparent', 'transparent'])},
      hover: {token: `${token}-soft-hover`, label: 'Hover', colors: mix(base, 20, ['transparent', 'transparent'])},
      foreground: {token: `${token}-soft-foreground`, label: 'Foreground', colors: base},
    } : undefined,
  };
}

const rolePalettes = {
  accent: role('--accent', [accent, accent], [snow, snow]),
  default: role('--default', ['oklch(94% 0.001 286.375)', 'oklch(27.4% 0.006 286.033)'], foreground, false),
  success: role('--success', [success, success], [eclipse, eclipse]),
  warning: role('--warning', ['oklch(0.7819 0.1585 72.33)', 'oklch(0.8203 0.1388 76.34)'], [eclipse, eclipse]),
  danger: role('--danger', ['oklch(0.6532 0.2328 25.74)', 'oklch(0.594 0.1967 24.63)'], [snow, snow]),
};

const stackedPalettes = {
  foreground: [
    {token: '--foreground', label: 'Foreground', colors: foreground},
    {token: '--muted', label: 'Muted', colors: muted},
    {token: '--segment', label: 'Segment', colors: segment},
    {token: '--overlay', label: 'Overlay', colors: surface},
    {token: '--link', label: 'Link', colors: foreground},
  ],
  background: [
    {token: '--background', label: 'Background', colors: background},
    {token: '--background-secondary', label: 'Secondary', colors: mix(background, 96, foreground)},
    {token: '--background-tertiary', label: 'Tertiary', colors: mix(background, 92, foreground)},
    {token: '--background-inverse', label: 'Inverse', colors: foreground},
  ],
  surface: [
    {token: '--surface', label: 'Surface', colors: surface},
    {token: '--surface-secondary', label: 'Secondary', colors: ['oklch(0.9524 0.0013 286.37)', 'oklch(0.257 0.0037 286.14)']},
    {token: '--surface-tertiary', label: 'Tertiary', colors: ['oklch(0.9373 0.0013 286.37)', 'oklch(0.2721 0.0024 247.91)']},
  ],
  separator: [
    {token: '--separator', label: 'Separator', colors: ['oklch(92% 0.004 286.32)', 'oklch(25% 0.006 286.033)'], outline: true},
    {token: '--separator-secondary', label: 'Secondary', colors: mix(surface, 85, foreground), outline: true},
    {token: '--separator-tertiary', label: 'Tertiary', colors: mix(surface, 81, foreground), outline: true},
  ],
  other: [
    {token: '--border', label: 'Border', colors: border},
    {token: '--backdrop', label: 'Backdrop', colors: ['rgb(0 0 0 / 50%)', 'rgb(0 0 0 / 60%)'], ink: ['#999999', '#a1a1aa']},
    {token: '--overlay', label: 'Overlay', colors: surface, outline: true},
    {token: '--segment', label: 'Segment', colors: segment, outline: true},
  ],
} satisfies Record<string, Swatch[]>;

const field = {
  base: {token: '--field-background', label: 'Bg', colors: surface},
  hover: {
    token: '--field-hover',
    label: 'Hover',
    colors: mix(surface, 90, foreground),
  },
  focus: {token: '--field-focus', label: 'Focus', colors: surface},
  placeholder: {token: '--field-placeholder', label: 'Placeholder', colors: muted},
  foreground: {token: '--field-foreground', label: 'Foreground', colors: foreground},
} satisfies Record<string, Swatch>;

const primitives: Swatch[] = [
  {token: '--white', label: 'White', colors: [white, white], outline: true},
  {token: '--black', label: 'Black', colors: [black, black]},
  {token: '--snow', label: 'Snow', colors: [snow, snow], outline: true},
  {token: '--eclipse', label: 'Eclipse', colors: [eclipse, eclipse]},
];

const modes = [{label: 'Light', index: 0, theme: 'light'}, {label: 'Dark', index: 1, theme: 'dark'}] as const;
type Mode = (typeof modes)[number];

function ColorSwatch({swatch, mode, label = swatch.label, variant = 'swatch', ink}: {
  swatch: Swatch;
  mode: Mode;
  label?: string;
  variant?: 'header' | 'label' | 'swatch';
  ink?: string;
}) {
  const tooltipId = useId();
  const [message, setMessage] = useState('');
  const color = swatch.colors[mode.index];

  async function copyColor(button: HTMLButtonElement) {
    const oklch = `oklch(from ${color} l c h / alpha)`;
    let value = color;

    if (CSS.supports('color', oklch)) {
      const previous = button.style.color;
      button.style.color = oklch;
      value = getComputedStyle(button).color;
      button.style.color = previous;
    }

    try {
      await navigator.clipboard.writeText(value);
      setMessage('복사됨');
    } catch {
      setMessage('복사 실패');
    }
  }

  return (
    <div className="color-swatch">
      <button
        type="button"
        className={`color-swatch__button color-swatch__button--${variant}`}
        style={{
          backgroundColor: variant === 'label' ? undefined : color,
          color: ink ?? swatch.ink?.[mode.index] ?? `oklch(from ${color} clamp(0, (0.66 - l) * 100, 1) 0 0 / 1)`,
        }}
        data-outline={swatch.outline || undefined}
        aria-label={`${mode.label} ${swatch.token} 색상 복사`}
        aria-describedby={tooltipId}
        onClick={event => void copyColor(event.currentTarget)}
        onBlur={() => setMessage('')}
      >
        <span>{label}</span>
        {variant === 'header' && <span className="color-swatch__mode">{mode.label}</span>}
      </button>
      <span id={tooltipId} role="tooltip" className="color-swatch__tooltip">
        {swatch.token}: {color}
      </span>
      <span role="status" className="color-swatch__announcement">{message}</span>
      {message && <span aria-hidden="true" className="color-swatch__status">{message}</span>}
    </div>
  );
}

function ColorRow({swatches, mode}: {swatches: Swatch[]; mode: Mode}) {
  return (
    <div className="color-section__row">
      {swatches.map(swatch => <ColorSwatch key={swatch.token} swatch={swatch} mode={mode} />)}
    </div>
  );
}

function ThemeBadge({mode}: {mode: Mode}) {
  return (
    <span className="color-section__theme-name"><span aria-hidden="true">⚙</span> {mode.label}</span>
  );
}

export function ColorSectionSideBySide({palette, label}: {palette: keyof typeof rolePalettes; label: string}) {
  const {solid, soft} = rolePalettes[palette];

  return (
    <div className="not-prose color-section color-section--comparison" data-color-palette={palette}>
      {modes.map(mode => (
        <div key={mode.theme} className="color-section__theme" data-preview-theme={mode.theme}>
          <ColorSwatch swatch={solid.base} mode={mode} label={label} variant="header" ink={solid.foreground.colors[mode.index]} />
          <div className={soft ? 'color-section__groups color-section__groups--split' : 'color-section__groups'}>
            <div
              className="color-section__group"
              style={{backgroundColor: solid.base.colors[mode.index], color: solid.foreground.colors[mode.index]}}
            >
              <span className="color-section__group-name">{label}</span>
              <ColorSwatch swatch={solid.hover} mode={mode} ink={solid.foreground.colors[mode.index]} />
              <ColorSwatch swatch={solid.foreground} mode={mode} />
            </div>
            {soft && (
              <div
                className="color-section__group"
                style={{
                  backgroundColor: surface[mode.index],
                  backgroundImage: `linear-gradient(${soft.base.colors[mode.index]}, ${soft.base.colors[mode.index]})`,
                }}
              >
                <ColorSwatch swatch={soft.base} mode={mode} label={`${label} Soft`} variant="label" ink={foreground[mode.index]} />
                <ColorSwatch swatch={soft.hover} mode={mode} ink={foreground[mode.index]} />
                <ColorSwatch swatch={soft.foreground} mode={mode} />
              </div>
            )}
          </div>
        </div>
      ))}
    </div>
  );
}

export function ColorSectionStacked({palette}: {palette: keyof typeof stackedPalettes}) {
  return (
    <div className="not-prose color-section color-section--stacked" data-color-palette={palette}>
      {modes.map(mode => (
        <div key={mode.theme} className="color-section__theme" data-preview-theme={mode.theme}>
          <ThemeBadge mode={mode} />
          <ColorRow swatches={stackedPalettes[palette]} mode={mode} />
        </div>
      ))}
    </div>
  );
}

export function ColorSectionFormField() {
  return (
    <div className="not-prose color-section color-section--fields" data-color-palette="field">
      {modes.map(mode => (
        <div key={mode.theme} className="color-section__theme" data-preview-theme={mode.theme}>
          <ThemeBadge mode={mode} />
          <div className="color-section__field">
            <div className="color-section__group color-section__field-states" style={{backgroundColor: field.base.colors[mode.index]}}>
              <ColorSwatch swatch={field.base} mode={mode} variant="label" ink={foreground[mode.index]} />
              <ColorSwatch swatch={field.hover} mode={mode} />
              <ColorSwatch swatch={field.focus} mode={mode} />
            </div>
            <div className="color-section__field-values">
              <ColorSwatch swatch={field.placeholder} mode={mode} />
              <ColorSwatch swatch={field.foreground} mode={mode} />
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}

export function ColorSectionPrimitive() {
  return (
    <div className="not-prose color-section" data-color-palette="primitive">
      <ColorRow swatches={primitives} mode={modes[0]} />
    </div>
  );
}
