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
 */
class GalleristPageDecoratorTest extends FunctionalTest {
	
	public static $fixture_file = 'gallerist/tests/GalleristPageDecoratorTest.yml';
	
	private $oldMarkupTemplate = null;
	private $oldGalleristActive = null;
	
	public function setUp() {
		parent::setUp();
		$this->oldMarkupTemplate = Object::get_static('GalleristPageDecorator', 'markup_template');
		Object::add_static_var('GalleristPageDecorator', 'markup_template', 'Gallerist', true);
		$this->oldGalleristActive = Object::get_static('Page', 'gallerist_active');
		Object::add_static_var('Page', 'gallerist_active', true, true);
		foreach($this->allFixtureIDs('Image') as $fileID) {
			$file = DataObject::get_by_id('Image', $fileID);
			copy(BASE_PATH . "/gallerist/images/{$file->Name}", BASE_PATH . "/{$file->Filename}");
		}
	}
	
	public function tearDown() {
		Object::add_static_var('GalleristPageDecorator', 'markup_template', $this->oldMarkupTemplate, true);
		Object::add_static_var('Page', 'gallerist_active', $this->oldGalleristActive, true);
		foreach($this->allFixtureIDs('Image') as $fileID) {
			$file = DataObject::get_by_id('Image', $fileID);
			$file->delete();
		}
		parent::tearDown();
	}
	
	public function testHeightCalculation() {
		$page = DataObject::get_one('Page', "\"URLSegment\" = 'page1'");
		$this->assertType('Page', $page);
		$this->assertEquals(600, $page->GalleristImageHeight());
	}
	
	public function testGalleristImage() {
		$page = DataObject::get_one('Page', "\"URLSegment\" = 'page1'");
		$this->assertType('Page', $page);
		$expectedImages = array(
			0 => 'assets/Test-800x800.png',
			1 => 'assets/Test-800x700.png',
			2 => 'assets/Test-800x600.png'
		);
		foreach ($expectedImages as $pos => $filename) {
			$this->assertEquals($filename, $page->GalleristImage($pos)->Filename);
		}
	}
	
	public function testMarkupTemplate() {
		$page = DataObject::get_one('Page', "\"URLSegment\" = 'page1'");
		$this->assertType('Page', $page);
		$markup = $page->Gallerist();
		$this->assertTrue((bool)$markup);
		$cssParser = new CSSContentParser($markup);
		$this->assertTrue((bool)$cssParser->getBySelector('#Gallerist'));
		$this->assertEquals(3, count($cssParser->getBySelector('#Gallerist .GalleristPageItem')));
	}
	
	public function testCMSFields() {
		$this->loginAs(1);
		$page = DataObject::get_one('Page', "\"URLSegment\" = 'page1'");
		$this->assertType('Page', $page);
		$response = $this->get("/admin/show/{$page->ID}");
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertTrue((bool)$this->cssParser()->getBySelector('#Form_EditForm_GalleristPageItems'));
		$this->assertEquals(3, count($this->cssParser()->getBySelector('#Form_EditForm_GalleristPageItems td.Image.Filename')));
	}
	
}

class GalleristPageDecoratorTest_SpecialPage extends Page {
	
	/**
	 * Override any project specific implementation of the
	 * GalleristImageWidth()-method to allow the unit tests to
	 * pass.
	 * 
	 * @see GalleristPageItem
	 */
	public function GalleristImageWidth() {
		return 0;
	}
	
}

