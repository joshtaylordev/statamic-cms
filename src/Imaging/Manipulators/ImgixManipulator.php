<?php

namespace Statamic\Imaging\Manipulators;

use Imgix\UrlBuilder;
use Statamic\Facades\Asset;

class ImgixManipulator extends Manipulator
{
    public function __construct(private readonly array $config = [])
    {
        //
    }

    public function getAvailableParams(): array
    {
        return [
            'ar',
            'auto',
            'bg',
            'bg-remove',
            'bg-remove-fallback',
            'bg-replace',
            'bg-replace-fallback',
            'bg-replace-neg-prompt',
            'blend',
            'blend-align',
            'blend-alpha',
            'blend-color',
            'blend-crop',
            'blend-fit',
            'blend-h',
            'blend-mode',
            'blend-pad',
            'blend-size',
            'blend-w',
            'blend-x',
            'blend-y',
            'blur',
            'border',
            'border-bottom',
            'border-left',
            'border-radius',
            'border-radius-inner',
            'border-right',
            'border-top',
            'bri',
            'ch',
            'chromasub',
            'colorquant',
            'colors',
            'con',
            'corner-radius',
            'crop',
            'cs',
            'dl',
            'dpi',
            'dpr',
            'duotone',
            'duotone-alpha',
            'exp',
            'expires',
            'faceindex',
            'facepad',
            'faces',
            'fill',
            'fill-color',
            'fill-gen-fallback',
            'fill-gen-neg-prompt',
            'fill-gen-pos',
            'fill-gen-prompt',
            'fill-gen-seed',
            'fill-gradient-cs',
            'fill-gradient-linear',
            'fill-gradient-linear-direction',
            'fill-gradient-radial',
            'fill-gradient-radial-radius',
            'fill-gradient-radial-x',
            'fill-gradient-radial-y',
            'fill-gradient-type',
            'fit',
            'flip',
            'fm',
            'fp-debug',
            'fp-x',
            'fp-y',
            'fp-z',
            'fps',
            'frame',
            'gam',
            'gif-q',
            'grid-colors',
            'grid-size',
            'h',
            'high',
            'htn',
            'hue',
            'interval',
            'invert',
            'iptc',
            'jpg-progressive',
            'loop',
            'lossless',
            'mark',
            'mark-align',
            'mark-alpha',
            'mark-base',
            'mark-fit',
            'mark-h',
            'mark-pad',
            'mark-rot',
            'mark-scale',
            'mark-tile',
            'mark-w',
            'mark-x',
            'mark-y',
            'mask',
            'mask-bg',
            'max-h',
            'max-w',
            'min-h',
            'min-w',
            'monochrome',
            'nr',
            'nrs',
            'orient',
            'pad',
            'pad-bottom',
            'pad-left',
            'pad-right',
            'pad-top',
            'page',
            'palette',
            'pdf-annotation',
            'prefix',
            'px',
            'q',
            'rect',
            'reverse',
            'rot',
            'sat',
            'sepia',
            'shad',
            'sharp',
            'skip',
            'svg-sanitize',
            'transparency',
            'trim',
            'trim-color',
            'trim-md',
            'trim-pad',
            'trim-sd',
            'trim-tol',
            'txt',
            'txt-align',
            'txt-clip',
            'txt-color',
            'txt-fit',
            'txt-font',
            'txt-line',
            'txt-line-color',
            'txt-pad',
            'txt-shad',
            'txt-size',
            'txt-width',
            'txt-x',
            'txt-y',
            'upscale',
            'usm',
            'usmrad',
            'vib',
            'w',
        ];
    }

    public function getUrl(): string
    {
        $url = $this->getBuilder()->createURL($this->getSourcePath(), $this->params);

        return (string) str($url)->replace('bg-remove=1', 'bg-remove=true');
    }

    private function getBuilder(): UrlBuilder
    {
        return new UrlBuilder(
            $this->config['domain'],
            signKey: $this->config['key'],
            includeLibraryParam: $this->config['ixlib'] ?? true,
        );
    }

    private function getSourcePath(): string
    {
        $source = $this->source;

        if ($this->getSourceType() === SourceType::AssetId) {
            $source = Asset::findOrFail($this->source);
        }

        return match ($this->getSourceType()) {
            SourceType::AssetId,
            SourceType::Asset => $source->path(),
            SourceType::Url,
            SourceType::Path => $source,
        };
    }

    public function getDataUrl(): string
    {
        return '';
    }

    public function getAttributes(): array
    {
        [$width, $height] = getimagesize($this->getUrl());

        return compact('width', 'height');
    }
}