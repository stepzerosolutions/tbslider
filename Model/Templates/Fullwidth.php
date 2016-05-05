<?php
/**
 * Copyright © 2015 Stepzero.Solutions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Stepzerosolutions\Tbslider\Model\Templates;

use Stepzerosolutions\Tbslider\Api\Data\TemplatesInterface as TemplatesInterface;
/**
 * @stepzerosolutions
 */
class Fullwidth 
implements TemplatesInterface
{
	protected $_sliderdata;
	protected $_sliderItems;
	protected $_storeManager;
	protected $_sliderResponsive;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
    }

	public function getSliderResponsiveData(){
		return $this->_sliderResponsive;
	}
	
	public function setSliderResponsiveData($_sliderResponsive){
		$this->_sliderResponsive = $_sliderResponsive;
	}
	
	
	public function getSliderData(){
		return $this->_sliderdata;
	}
	
	public function setSliderData($sliderdata){
		$this->_sliderdata = $sliderdata;
	}
	
	public function getSlideritems(){
		return $this->_sliderItems;
	}
	
	public function setSlideritems($items){
		$this->_sliderItems = $items;
	}
	
	public function renderSlider(){
		$cid='generic_slider';
		$output = '';
		$first=true;
		$slidto = 0;
		$ol = '<ol class="carousel-indicators">';
		foreach( $this->getSlideritems() as $slide ){
			$slider = $slide->getData();
				$ol .= '<li data-target="#'.$cid.'" data-slide-to="'.$slidto.'"';
				$slidto++;
				if( $first ) $ol .= ' class="active"';
				$ol .= ' ></li>

				';

				$url = !empty($slider['slider_url'])?'<a href="'.$slider['slider_url'].'" title="'.$slider['slideritem_title'].'">':'';
				$urlend = !empty($slider['slider_url'])?'</a>':'';
				$output .= '<div class="item ';
				if( $first ) { $output .= 'active'; $first=false; }
				$output .= '">';
				$default = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA ) . $slide->getSliderImagePath();
				$filename = $default;
				
				$tmpimg = $slide->getSliderImageMdPath();
				$filenamemd = ( !empty($tmpimg) )?$this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA ) . 
				$slide->getSliderImageMdPath():$default;
				
				$tmpimg = $slide->getSliderImageSmPath();
				$filenamesm = ( !empty($tmpimg) )?$this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA ) . 
				$slide->getSliderImageSmPath():$default;
				
				$tmpimg = $slide->getSliderImageXsPath();
				$filenamexs = ( !empty($tmpimg) )?$this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA ) . 
				$slide->getSliderImageXsPath():$default;
				$responsive = $this->getSliderResponsiveData();			

				$output .= $url.'<img class="sztbslider" data-src="'.$filename.'" 
				data-smsrc="'.$filenamesm.'" data-mdsrc="'.$filenamemd.'"  data-xssrc="'.$filenamexs.'" 
				alt="'.$slider['slideritem_title'].'" width="100%" />'.$urlend;
				
				if( ! empty( $slider['slideritem_description'] ) ){
					$output .= '<div class="carousel-caption">
									';
					$output .= $slider['slideritem_description'];
    	            $output .= '	';
	                $output .= '   </div>';
				}
                $output .= '</div>';
				
		}
		$ol .= '</ol>';
		if( empty( $output ) ) return false;
		
		$outputRender = '<div id="'.$cid.'" class="carousel slide" data-ride="carousel">';
		$sliderclass = $this->_sliderdata->getSliderClass();
		$cssClass = ( !empty($sliderclass) )?$this->_sliderdata->getSliderClass():'';
		
		$sliderduration = $this->_sliderdata->getSliderDuration();
		$dataInterval = ( !empty($sliderduration) )?$this->_sliderdata->getSliderDuration():5000;
		
		$sliderwrap = $this->_sliderdata->getWrap();
		$dataWrap = ( !empty($sliderwrap) )?$this->_sliderdata->getWrap():true;
		
		$sliderkeyboard = $this->_sliderdata->getKeyboard();
		$dataKeyboard = ( !empty($sliderkeyboard) )?$this->_sliderdata->getKeyboard():true;
		
		$sliderphover = $this->_sliderdata->getPauseonhover();
		$dataPause = ( !empty($sliderphover) )?"hover":"false";
		
		$outputRender .= '<div class="carousel-inner szcarousel '.$cssClass.'" 
										data-interval="'.$dataInterval.'" 
										data-wrap="'.$dataWrap.'" 
										data-keyboard="'.$dataKeyboard.'" 
										data-pause="'.$dataPause.'" 
										role="listbox" 
										data-responsivewidth="'.$responsive.'" >
					' . $ol . $output . '
					</div>';
					
		if( !$this->_sliderdata->getHidenavigation() ) {
			if( count( $this->_sliderItems->getData() )>1 ) {
				$outputRender .= '
					 <!-- Controls -->
					  <a class="left carousel-control" href="#'.$cid.'" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					  </a>
					  <a class="right carousel-control" href="#'.$cid.'" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					  </a>
				';
			}
		}
		$outputRender .= '</div>';
		return $outputRender;
	}
	
	
	public function renderSliderLayout(){
		$slider = 'div.slider_'.$this->getSliderData()->getID().'{';
		$sliderwrap = '.slidercontainer{margin:0 auto;';
		$slideraftermaxwidth = '';
		$getsliderWidth = $this->getSliderData()->getSliderWidth();
		if( !empty( $getsliderWidth ) ) {
			if( strstr($this->getSliderData()->getSliderWidth(), "%")!=false ) {
				$sliderWidth = ( strstr($this->getSliderData()->getSliderWidth(), "px") )?$this->getSliderData()->getSliderWidth().'px':$this->getSliderData()->getSliderWidth();
			} else {
				$sliderWidth = $this->getSliderData()->getSliderWidth();
			}
			$slider .= sprintf( "width:%s;", $sliderWidth );
			$sliderwrap .= sprintf( "width:%s;", $sliderWidth );
			$slideraftermaxwidth .= '@media (max-width: '.$sliderWidth.'){.slidercontainer,div.slider_'.$this->getSliderData()->getID().'{width:100%;}}';
		} else {
			$slider .= "width:100%;";
			$sliderwrap .= "width:100%;";
		}
		
		$getsliderHeight = $this->getSliderData()->getSliderHeight();
		if( !empty( $getsliderHeight ) ) {
			if( strstr($this->getSliderData()->getSliderHeight(), "%")!=false ) {
				$sliderHeight = ( strstr($this->getSliderData()->getSliderHeight(), "px") )?$this->getSliderData()->getSliderHeight().'px':$this->getSliderData()->getSliderHeight();
			} else {
				$sliderHeight = $this->getSliderData()->getSliderHeight();
			}
			$slider .= sprintf( "height:%s;", $sliderHeight );
			$slider .= "overflow:hidden;";
		}
		$slider .= '}';
		$sliderwrap .= '}';
	
		$sliderItems = 'div.slider_'.$this->getSliderData()->getID().' .carousel-item{';
		
		
		if( !empty( $getsliderWidth ) ) {
			$sliderItems .= "width:100%;";
		} 
		$sliderItems .= '}';

		$mobileDevice = '@media (max-width: 768px){';
		$mobileDevice .= '.slidercontainer{';
		
		$getsliderWidthXs = $this->getSliderData()->getSliderWidthxs();
		if( !empty( $getsliderWidthXs ) ) {
			$mobileDeviceWidth = sprintf( "width:%dpx;", $this->getSliderData()->getSliderWidthxs() );
		} else {
			$mobileDeviceWidth = "width:100%;";
			$mobileDeviceWidth .= "max-width:768px;";
		}
		$mobileDevice .= $mobileDeviceWidth . '}';
		$mobileDevice .= 'div.slider_'.$this->getSliderData()->getID().'{';
		$mobileDevice .= $mobileDeviceWidth ;
		$mobileDevice .= 'height:auto;';
		$mobileDevice .= '}';
		$mobileDevice .= '}';
		
	
		$smallDevice = '@media (max-width: 922px){';
		$smallDevice .= '.slidercontainer{';
		
		$getsliderWidthSm = $this->getSliderData()->getSliderWidthsm();
		if( !empty( $getsliderWidthSm ) ) {
			$smallDeviceWidth = sprintf( "width:%dpx;", $this->getSliderData()->getSliderWidthsm() );
		} else {
			$smallDeviceWidth = "width:100%;";
			$smallDeviceWidth .= "max-width:922px;";
		}
		$smallDevice .= $smallDeviceWidth . '}';
		$smallDevice .= 'div.slider_'.$this->getSliderData()->getID().'{';
		$smallDevice .= $smallDeviceWidth ;
		$smallDevice .= 'height:auto;';
		$smallDevice .= '}';
		$smallDevice .= '}';
		
		
		$mediumDevice = '@media (max-width: 1024px){';
		$mediumDevice .= '.slidercontainer{';
		
		$getsliderWidthMd = $this->getSliderData()->getSliderWidthmd();
		if( !empty( $getsliderWidthMd ) ) {
			$mediumDeviceWidth = sprintf( "width:%dpx;", $this->getSliderData()->getSliderWidthmd() );
		} else {
			$mediumDeviceWidth = "width:100%;";
			$mediumDeviceWidth .= "max-width:1024px;";
		}
		$mediumDevice .= $mediumDeviceWidth . '}';
		$mediumDevice .= 'div.slider_'.$this->getSliderData()->getID().'{';
		$mediumDevice .= $mediumDeviceWidth ;
		$mediumDevice .= 'height:auto;';
		$mediumDevice .= '}';
		$mediumDevice .= '}';
		 

		$prints = $sliderwrap . $slider . $sliderItems . $mediumDevice . $smallDevice . $mobileDevice . $slideraftermaxwidth;
		return sprintf( "<style>%s</style>" , $prints);
	}
}
