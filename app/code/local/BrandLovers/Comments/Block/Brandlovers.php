<?php
class BrandLovers_Comments_Block_Brandlovers extends Mage_Core_Block_Template
{
	
	/**
     * Default script attributes
     *
     * @var array
     * @access private
     */
    private $scriptAttributes = [
        'src' => '//api.brandlovers.com/v1/plugin.js',
        'data-name' => 'bl-script',
        'async' => 'async',
    ];

    /**
     * Default element attributes
     *
     * @var array
     * @access private
     */
    private $elementAttributes = [
        'class' => 'bl-comment'
    ];
	
	
	public function _prepareLayout()
    {
		$this->scriptAttributes['data-language'] = isset($options['data-language']) ? $options['data-language'] : 'pt';
		return parent::_prepareLayout();
    }
	
	/**
     * Print script which defined attributes
     *
     * @access public
     * @return void
     */
    public function printScript()
    {
        $domDocument = new DOMDocument();
        $domScript = $domDocument->createElement('script');
        foreach ($this->scriptAttributes as $key => $value) {
            $domAttribute = $domDocument->createAttribute($key);
            $domAttribute->value = $value;
            $domScript->appendChild($domAttribute);
        }
        $domDocument->appendChild($domScript);
        echo $domDocument->saveHtml();
    }
	
	/**
     * Render BL HTML
     *
     * @access public
     * @param array $attributes
     * @return String
     */
    public function getBrandLoversHtml(array $attributes)
    {
		if(!Mage::getStoreConfig('comments/general/enabled')){
			return '';
		}
        $defaultOptions = Mage::helper('comments')->getBrandLoversOptions();
		$domDocument = new DOMDocument();
        $domElement = $domDocument->createElement('div');
        $attributesToInsert = array_merge($attributes, $this->elementAttributes);
        // default options
        $domElement = $this->addAttributesToElement($domElement, $defaultOptions, $domDocument);
        // custom attributes
		$domElement = $this->addAttributesToElement($domElement, $attributesToInsert, $domDocument);
        $domDocument->appendChild($domElement);

        return $domDocument->saveHtml();
    }

    /**
     * Add attributes to DOMElement
     *
     * @access private
     * @param DOMElement $domElement
     * @param array $attributes
     * @param DOMDocument $domDocument
     * @return DOMElement
     */
    private function addAttributesToElement(DOMElement $domElement, array $attributes, DOMDocument $domDocument)
    {
        foreach ($attributes as $key => $value) {
            $domAttribute = $domDocument->createAttribute($key);
            $domAttribute->value = $value;
            $domElement->appendChild($domAttribute);
        }

        return $domElement;
    }
}