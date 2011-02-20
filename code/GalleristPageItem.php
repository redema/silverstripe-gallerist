<?php

/**
 * Copyright 2011 Charden Reklam Östersund AB (http://charden.se/)
 * Erik Edlund <erik@charden.se>
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
 * * Neither the name of Charden Reklam, nor the names of its contributors may be
 *   used to endorse or promote products derived from this software without specific
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
 * Representation of a gallery item.
 */
class GalleristPageItem extends DataObject {
	
	public static $db = array(
		'Title' => 'Text',
		'Description' => 'Text',
		'Link' => 'Text',
		'Sort' => 'Int'
	);
	
	public static $has_one = array(
		'Image' => 'Image',
		'Page' => 'Page'
	);
	
	public static $has_one_on_versioning = array(
		'Page' => true
	);
	
	public static $has_one_on_delete = array(
		'File' => 'delete',
		'Page' => 'delete'
	);
	
	public static $extensions = array(
		"Versioned('Stage', 'Live')"
	);
	
	public static $default_sort = 'Sort';
	
	public function fieldLabels($includerelations = true) {
		$labels = parent::fieldLabels($includerelations);
		
		$labels['Title'] = _t('GalleristPageItem.TITLE', 'Title');
		$labels['Description'] = _t('GalleristPageItem.DESCRIPTION', 'Description');
		$labels['Link'] = _t('GalleristPageItem.LINK', 'Link');
		$labels['Sort'] = _t('GalleristPageItem.SORT', 'Sort');
		
		$labels['Thumbnail'] = _t('GalleristPageItem.THUMBNAIL', 'Thumbnail');
		$labels['PublishedToLive'] = _t('GalleristPageItem.PUBLISHEDTOLIVE', 'Published?');
		
		if ($includerelations) {
			$labels['Image'] = _t('GalleristPageItem.IMAGE', 'Image');
			$labels['Page'] = _t('GalleristPageItem.PAGE', 'Page');
		}
		
		return $labels;
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->replaceField('Title', new TextField('Title', $this->fieldLabel('Title')));
		$fields->replaceField('Link', new TextField('Link', $this->fieldLabel('Link')));
		$fields->removeByName('Version');
		$fields->removeByName('Versions');
		
		return $fields;
	}
	
	public function PublishedToLive() {
		$item = Versioned::get_one_by_stage('GalleristPageItem', 'Live',
			"\"GalleristPageItem\".\"ID\" = {$this->ID}");
		if ($item) {
			return $item->stagesDiffer('Stage', 'Live')?
				_t('GalleristPageItem.PUBLISHEDTOLIVEYESWITHCHANGES', 'Yes, with unpublished changes'):
				_t('GalleristPageItem.PUBLISHEDTOLIVEYES', 'Yes');
		}
		return _t('GalleristPageItem.PUBLISHEDTOLIVENO', 'No');
	}
	
	public function Image() {
		$image = $this->getComponent('Image');
		if ($image->exists()) {
			if ($this->PageID && ($width = $this->Page()->GalleristImageWidth()))
				$image = $image->SetWidth($width);
		}
		return $image;
	}
	
	public function Thumbnail() {
		$image = $this->Image();
		if ($image->exists()) {
			return $image->CroppedImage(25, 25);
		}
		return null;
	}
	
}

