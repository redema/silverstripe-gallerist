<?php

/**
 * Copyright (c) 2012, Redema AB - http://redema.se/
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 * * Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * 
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * 
 * * Neither the name of Redema, nor the names of its contributors may be used
 *   to endorse or promote products derived from this software without specific
 *   prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * 
 */
class GalleristPageDecorator extends DataObjectDecorator {
	
	/**
	 * Enter file names without extensions.
	 * #@+
	 */
	
	/**
	 * Template for the markup rendered by Gallerist().
	 * @var string
	 */
	public static $markup_template = 'Gallerist';
	
	/**
	 * Template for the custom CSS rendered for the gallerist
	 * markup.
	 * @var string
	 */
	public static $css_template = 'Gallerist_Css';
	
	/**
	 * #@-
	 */
	
	public function extraStatics() {
		return array(
			'has_many' => array(
				'GalleristPageItems' => 'GalleristPageItem'
			),
			'field_labels' => array(
				'GalleristPageItemTab' => _t('GalleristPageDecorator.GALLERISTPAGEITEMTAB', 'Page gallery items'),
				'GalleristPageItems' => _t('GalleristPageDecorator.GALLERISTPAGEITEMS', 'Page gallery items')
			)
		);
	}
	
	public function updateCMSFields(FieldSet &$fields) {
		if (Object::get_static($this->owner->class, 'gallerist_active')) {
			$complexTableFieldClass = class_exists('OrderableComplexTableField')?
				'OrderableComplexTableField': 'ComplexTableField';
			$complexTableField = new $complexTableFieldClass(
				$this->owner,
				'GalleristPageItems',
				'GalleristPageItem',
				array(
					'Thumbnail' => singleton('GalleristPageItem')->fieldLabel('Thumbnail'),
					'Image.Filename' => singleton('Image')->fieldLabel('Filename'),
					'Title' => singleton('GalleristPageItem')->fieldLabel('Title'),
					'Description' => singleton('GalleristPageItem')->fieldLabel('Description'),
					'PublishedToLive' => singleton('GalleristPageItem')->fieldLabel('PublishedToLive')
				)
			);
			
			$complexTableField->setPermissions(array('add', 'edit', 'delete'));
			$items = $this->owner->GalleristPageItems();
			$complexTableField->setPageSize($items? $items->Count(): 0);
			
			$fields->findOrMakeTab('Root.Content.GalleristPageItems', $this->owner->fieldLabel('GalleristPageItemTab'));
			$fields->addFieldToTab('Root.Content.GalleristPageItems', $complexTableField);
		}
	}
	
	public function Gallerist() {
		if (Object::get_static($this->owner->class, 'gallerist_active')) {
			return $this->owner->renderWith(Object::get_static('GalleristPageDecorator',
				'markup_template'));
		}
		return '';
	}
	
	public function GalleristImage($pos = 0) {
		$items = $this->owner->GalleristPageItems();
		if ($items) {
			$first = $items->getRange($pos, 1)->First();
			if ($first)
				return $first->Image();
		}
		return null;
	}
	
	/**
	 * Fallbacks for width/height getters.
	 * 
	 * @return integer
	 * #@+
	 */
	
	public function GalleristImageWidth() {
		// We can never guess a sane value for the image width unless
		// given one. Since the gallery is contained in a block element
		// it will inherit its parents width, which should be a good
		// default.
		return 0;
	}
	
	public function GalleristImageHeight() {
		// A good guess for a height value is to find the shortest
		// image and use its height.
		$height = 0;
		$galleryItems = $this->owner->GalleristPageItems();
		if ($galleryItems) foreach ($galleryItems as $item) {
			$image = $item->Image();
			if ($image->exists() && ($height == 0 || $image->getHeight() < $height)) {
				$height = $image->getHeight();
			}
		}
		return $height;
	}
	
	/**
	 * #@-
	 */
}

