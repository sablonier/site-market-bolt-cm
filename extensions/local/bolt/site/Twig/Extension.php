<?php

namespace Bolt\Extensions\Helper;

use forxer\Gravatar\Gravatar;
use Symfony\Component\VarDumper\VarDumper;
use Twig_Extension as TwigExtension;

class Extension extends TwigExtension
{
    public $statusTemplate = '<div class="buildstatus ui icon label %s" data-content="%s"><i class="icon %s"></i> %s <span class="version">%s</span></div>';

    public function getFunctions()
    {
        return [
            'buildStatus'  => new \Twig_Function_Method($this, 'buildStatus', ['is_safe' => ['html']]),
            'gravatar'     => new \Twig_Function_Method($this, 'gravatar', ['is_safe' => ['html']]),
            'packageIcon'  => new \Twig_Function_Method($this, 'packageIcon', ['is_safe' => ['html']]),
            'dump'         => new \Twig_Function_Method($this, 'printDump', ['is_safe' => ['html']]),
            'getenv'       => new \Twig_Function_Method($this, 'getenv', ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            'humanTime'  => new \Twig_SimpleFilter('humanTime', [$this, 'humanTime']),
        ];
    }

    public function buildStatus($build, $options = [])
    {
        if (!$build || $build->testStatus === 'pending') {
            return sprintf($this->statusTemplate, 'orange', 'This version is currently awaiting a test result', 'wait', 'not setup', '');
        }

        if ($build->phpTarget) {
            $php = str_replace('php', '', $build->phpTarget);
            $php = substr_replace($php, '.', 1, 0);
            $php .= '+';
        } else {
            $php = '5.6';
        }

        if ($build->testStatus === 'approved') {
            return sprintf($this->statusTemplate, 'green', 'This version is an approved build', 'checkmark', $build->testStatus, 'for PHP ' . $php);
        }

        if ($build->testStatus === 'failed') {
            return sprintf($this->statusTemplate, 'red', 'This version is not an approved build', 'remove', $build->testStatus, 'for PHP ' . $php);
        }
    }

    public function gravatar($email, $options = [])
    {
        return Gravatar::image($email);
    }

    public function printDump($var)
    {
        return VarDumper::dump($var);
    }

    public function humanTime($time, $suffix = '')
    {
        if ($time instanceof \DateTime) {
            $time = $time->getTimestamp();
        }

        if (!$time) {
            return 'never';
        }

        $time = time() - $time; // to get the time since that moment

        $tokens = [
            31536000 => 'year',
            2592000  => 'month',
            604800   => 'week',
            86400    => 'day',
            3600     => 'hour',
            60       => 'minute',
            1        => 'second',
        ];

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $numberOfUnits = floor($time / $unit);

            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . $suffix;
        }
    }

    public function packageIcon($package)
    {
        if ($ico = $package->getIcon()) {
            if (strpos('//', $ico)) {
                return $package->getIcon();
            } else {
                $ico = str_replace('github.com', 'raw.githubusercontent.com', $package->getSource());
                $ico = $ico . '/master/' . $package->getIcon();

                return $ico;
            }
        }

        return '/images/' . $package->getType() . '.png';
    }

    public function getenv($key)
    {
        return getenv($key);
    }

    public function getName()
    {
        return 'bolt_helper';
    }
}
