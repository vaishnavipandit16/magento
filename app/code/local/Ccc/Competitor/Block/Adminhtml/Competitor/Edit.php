<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'competitor';
        $this->_controller = 'adminhtml_competitor';
        parent::__construct();
    }
}
?>

<script type="text/javascript">
var productTemplateSyntax = /(^|.|\r|\n)({{(\w+)}})/;

function setSettings(urlTemplate, setElement) {
    var template = new Template(urlTemplate, productTemplateSyntax);
    setLocation(template.evaluate({
        attribute_set: $F(setElement)
    }));
}
</script>