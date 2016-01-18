<?
/**
 * Venobox for Contao
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2015 POSTYOU
 *
 * @package venobox
 * @author  Gerald Meier
 * @link    http://www.postyou.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
namespace postyou;


class VenoBox extends \ContentElement
{

    protected $strTemplate = "ce_venobox";
    private $boxID;
    private $galleryIndex = 1;

    public function __construct($objElement, $strColumn='main'){
        parent::__construct($objElement);
        VenoHelper::loadVenoScripts();
    }
    
    public function generate()
    {

        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . utf8_strtoupper('VenoBox') . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Compile the content element
     */
    protected function compile()
    {
        $this->boxID = VenoHelper::getVenoBoxID();


        $this->Template->html = $this->getVenoElemsHtml($this->venoList,$this->boxID,$this->galleryIndex);
        $this->Template->boxClass = VenoHelper::getVenoBoxClass($this->boxID);
        $this->Template->js=$this->getJs(VenoHelper::getVenoBoxClass($this->boxID));

    }

    private function getVenoElemsHtml($vlist=null,$boxId,$galleryIndex){
        $html = "";
        if (isset($vlist)) {
            $list = unserialize($vlist);

            foreach ($list as $key => $elem) {
                $linkCssClass = "";
                if ($key == 0) {
                    $linkCssClass .= "first ";
                }
                if ($key == count($list) - 1) {
                    $linkCssClass .= "last";
                }
                $vElem = new VenoElement($elem, $boxId, $galleryIndex, $linkCssClass);
                $html .= $vElem->buildHtml() . "\n";
            }
        }
        return $html;

    }

     public static function getJs($boxClass){
        return "<script type=\"text/javascript\">
        $(document).ready(function() {
            var venoOptions={}
            if(typeof venobox_post_open_callback  != 'undefined' && $.isFunction(venobox_post_open_callback))
                venoOptions[\"post_open_callback\"]=venobox_post_open_callback;".
            "$('.".$boxClass."').venobox(venoOptions);".
            "});</script>";
    }

}


?>
