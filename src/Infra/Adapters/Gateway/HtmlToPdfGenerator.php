<?php

namespace App\Infra\Adapters\Gateway;

use App\App\Contracts\Gateway\PdfGeneratorInterface;
use App\App\Contracts\Repository\FortuneProviderInterface;
use App\Domain\Entity\Report;
use App\Domain\Entity\User;
use App\Domain\Exceptions\PdfGenerationException;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

readonly class HtmlToPdfGenerator implements PdfGeneratorInterface
{
    public function __construct(
        private FortuneProviderInterface $fortuneProvider,
        private string $projectDir
    ) {}

    public function generate(User $user, Report $report): string
    {
        try {
            $outputDir = "{$this->projectDir}/public/reports/";
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
            }

            $fileName = "report_{$report->getProcessId()}.pdf";
            $filePath = $outputDir . $fileName;
            $name = htmlspecialchars($user->getName() . ' ' . $user->getFamilyName());
            $sign = htmlspecialchars($user->getZodiac()?->getSign() ?? 'Unknown');
            $period = str_pad($report->getMonth(), 2, '0', STR_PAD_LEFT) . '/' . $report->getYear();
            $fortune = htmlspecialchars($this->fortuneProvider->getRandomFortune());
            $date = new \DateTime()->format('d/m/Y');
            $signSymbol = $this->getSignSymbol($sign);

            $html = <<<HTML
            <page backtop="8mm" backbottom="8mm" backleft="10mm" backright="10mm">
                <table style="width: 100%; border: 3px solid #4c1d95; background-color: #ede9fe; padding: 2mm;">
                    <tr><td>
                    <table style="width: 100%; border: 2px solid #7c3aed; background-color: #f5f0ff; padding: 2mm;">
                        <tr><td>
                        <table style="width: 100%; border: 1px solid #c4b5fd; background-color: #faf5ff;">
                            <tr>
                                <td style="text-align: center; font-size: 13px; color: #a855f7; letter-spacing: 2px; font-family: times; padding-top: 6mm;">
                                    ~ F R E E  A S T R O ~
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 28px; color: #6b21a8; font-family: times;">
                                    Monthly Horoscope Report
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 2px solid #c4b5fd;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 22px; color: #6b21a8; font-family: times;">
                                    [{$signSymbol}]
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 22px; color: #7c3aed; font-weight: bold; font-family: times;">
                                    {$sign}
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 2px solid #c4b5fd;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%; font-size: 13px;">
                                        <tr>
                                            <td style="width: 35%; text-align: right; color: #a855f7; font-weight: bold;">Name:</td>
                                            <td style="color: #3b0764;">{$name}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%; text-align: right; color: #a855f7; font-weight: bold;">Period:</td>
                                            <td style="color: #3b0764;">{$period}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%; text-align: right; color: #a855f7; font-weight: bold;">Generated:</td>
                                            <td style="color: #3b0764;">{$date}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 16px; color: #6b21a8; font-weight: bold; font-family: times; padding-top: 4mm;">
                                    Your Cosmic Reading
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 4mm;">
                                    <table style="width: 100%; background-color: #ede9fe; border-left: 4px solid #7c3aed;">
                                        <tr>
                                            <td style="font-size: 14px; color: #3b0764; line-height: 1.6; font-style: italic; font-family: times; padding: 4mm;">
                                                {$fortune}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 10px; color: #a78bfa; font-family: times; padding-top: 8mm; padding-bottom: 6mm;">
                                    Generated by FreeAstroAPI ~ The stars have spoken
                                </td>
                            </tr>
                        </table>
                        </td></tr>
                    </table>
                    </td></tr>
                </table>
            </page>
            HTML;
            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML($html);
            $html2pdf->output($filePath, 'F');
            return $filePath;
        } catch (Html2PdfException $exception) {
            throw new PdfGenerationException($exception->getMessage());
        }
    }

    private function getSignSymbol(string $sign): string
    {
        return match ($sign) {
            'Aries' => 'Y',
            'Taurus' => 'V',
            'Gemini' => 'II',
            'Cancer' => '69',
            'Leo' => 'SL',
            'Virgo' => 'nP',
            'Libra' => '=',
            'Scorpio' => 'M~',
            'Sagittarius' => '->',
            'Capricorn' => 'Vq',
            'Aquarius' => '~~',
            'Pisces' => '><',
            default => '*',
        };
    }
}
