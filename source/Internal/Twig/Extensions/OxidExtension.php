<?php

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class OxidExtension
 */
class OxidExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [new TwigFunction('oxid_include_widget', [$this, 'oxidIncludeWidget'])];


        $sSuffix = isset( $params['suffix'] ) ? $params['suffix'] : 'NO_SUFFIX';
        $blShowError = isset( $params['noerror']) ? !$params['noerror'] : true ;

        $iLang = $oLang->getTplLanguage();

        if( !$blAdmin && $oShop->isProductiveMode() ) {
            $blShowError = false;
        }

        try {
            $sTranslation = $oLang->translateString( $sIdent, $iLang, $blAdmin );
            $blTranslationNotFound = !$oLang->isTranslated();
            if ( 'NO_SUFFIX' != $sSuffix ) {
                $sSuffixTranslation = $oLang->translateString( $sSuffix, $iLang, $blAdmin );
            }
        } catch (\OxidEsales\Eshop\Core\Exception\LanguageException $oEx ) {
            // is thrown in debug mode and has to be caught here, as smarty hangs otherwise!
        }

        if( $blTranslationNotFound && isset( $params['alternative'] ) ) {
            $sTranslation = $params['alternative'];
            $blTranslationNotFound = false;
        }

        if ( !$blTranslationNotFound ) {
            if ( $aArgs !== false ) {
                if ( is_array( $aArgs ) ) {
                    $sTranslation = vsprintf( $sTranslation, $aArgs );
                } else {
                    $sTranslation = sprintf( $sTranslation, $aArgs );
                }
            }

            if ( 'NO_SUFFIX' != $sSuffix ) {
                $sTranslation .= $sSuffixTranslation;
            }

        } elseif( $blShowError ) {
            $sTranslation = 'ERROR: Translation for '.$sIdent.' not found!';
        }

        stopProfile("smarty_function_oxmultilang");

        return $sTranslation;
    }

    private function getIncludeWidgetParentViews($params)
    {
        $parentViews = null;
        if(!empty($params["_parent"])) {
            $parentViews = explode("|", $params["_parent"]);
        }
        return $parentViews;
    }

    private function getIncludeWidgetFormattedParams($params)
    {
        unset($params['cl']);
        if(!empty($params["_parent"])) {
            unset($params["_parent"]);
        }
        return $params;
    }

}