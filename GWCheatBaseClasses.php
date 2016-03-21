<?php
	// Base Classes
	include_once "GWCheatGlobal.php";
	class Cheat {
		var $name = "Unnamed Cheat";
		public function execute() {
			echo "[" . $this->name . "]" . PHP_EOL;
			$this->payload();
			echo PHP_EOL;
		}
		
		public function payload() {
			
		}
	}
	
	
//	
//	class GWPointer {
//		public $address = 0x0;
//		public $size = 1;
//		function __construct($address, $size) {
//			$this->address = $address;
//			$this->size = $size;
//		}
//		
//		public function setDataToValue() {
//			switch ($this->size) {
//				case 1:
//					setDataFromMemory8($this->address);
//					break;
//				case 2:
//					setDataFromMemory16($this->address);
//					break;
//				case 4:
//					setDataFromMemory32($this->address);
//					break;
//				default:
//					break;
//			}
//		}
//	}
//	
//	/*
//	DataType: A simple name:size dictionary.
//		Not gonna make it a class cuz it's too simple.
//	*/
//	class GWArray {
//		public $address;
//		public $dataType;
//		public $count;
//		public $dataSize = 0;
//		
//		function __construct($address, $dataType) {
//			$this->address = $address;
//			$this->dataType = $dataType;
//			
//			foreach ($this->dataType as $name => $size) {
//				$this->dataSize += $size;
//			}
//			
//		}
//		
//		public function objectAtIndex($index) {
//			$ret = array();
//			
//			foreach ($this->dataType as $name => $size) {
//				
//			}
//			
//		}
//		
//		public function pushObject() {
//			
//		}
//		
//		public function popObject() {
//			
//		}
//		
//	}
//	
//	// I need some more registers...
//	/*
//	OFFSET = USER DEFINED
//	----- START REGS: Ram will not be recovered
//	-- A
//	8
//	8
//	8
//	8
//	-- X
//	8
//	8
//	8
//	8
//	-- Y
//	8
//	8
//	8
//	8
//	----- END REGS, START STACK: Up to coder. Maybe I can do a dynamic...
//	8
//	8
//	8
//	8
//	--
//	8
//	8
//	8
//	8
//	--
//	
//	
//	*/
//	
//	class GWReg {
//		public $size = 4; // in bytes
//		public $offset;
//		function __construct($size, $offset) {
//			$this->offset = $offset;
//			$this->size = $size;
//		}
//		
//		function getValue() {
//			
//		}
//	}
//	
//	/*
//	GWProcessor will require full control flow
//	I think might be able to pull off a
//		emulation->preprocessed system...
//	*/
//	
//	class GWProcessor {
//		var $offset;
//		var $A, $X, $Y;
//		var $stackSizes = array();
//		var $stackPointer;
//		var $sizeClass = 4;
//		var $stack = new GWArray();
//		
//		function __construct($offset) {
//			$this->offset = $offset;
//			$this->A = new GWReg(4, 0);
//			$this->X = new GWReg(4, 4);
//			$this->Y = new GWReg(4, 8);
//			$this->stackPointer =  12;
//		}
//		
//		/* PROCESSOR */
//		
//		function setOffset($value) {
//			
//		}
//		
//		function setOffsetFromMemory($address) {
//			
//		}
//		
//		function getRegVal($reg) {
//			
//		}
//		
//		function pop($ptr) {
//			// *(address) = stack[SP--]
//			
//		}
//		
//		function pushData() {
//		}
//		
//		function push($value) {
//			if ($value > 0xffff) {
//				// 32 bit
//				array_push($this->stackSizes, 4);
//				
//			} else if ($value > 0xff) {
//				// 16 bit
//				array_push($this->stackSizes, 2);
//				
//			} else {
//				// 8 bit
//				array_push($this->stackSizes, 1);
//			}
//			
//		}
//		
//		function pushPtrVal($ptr) {
//			switch ($ptr->size) {
//				case 1:
//					$ptr->setDataToValue();
//					writeDataToMemory8($address)
//					break;
//				case 2:
//					
//					break;
//				case 4:
//					
//					break;
//				default:
//					break;
//			}
//		}
//		
//		function setSizeClass($size) {
//			// TODO: add size checking (must be 1, 2, or 4)
//			$this->sizeClass = $size;
//			$this->A->size = $size;
//			$this->X->size = $size;
//			$this->Y->size = $size;
//			
//			switch ($this->sizeClass) {
//				case 1:
//					
//					break;
//				case 2:
//					
//					break;
//				case 4:
//					
//					break;
//				default:
//					die("Bad size class");
//					break;
//			}
//		}
//		/* END PROCESSOR */
//		
//		/* PROCESSOR Backend */
//		public function writeValueToAddress($value, $address) {
//			
//			$newOffset = $value & 0xF0000000;
//			
//			
//			switch ($this->sizeClass) {
//				case 1:
//					memWrite8($address, $value);
//					break;
//				case 2:
//					memWrite16($address, $value);
//					break;
//				case 4:
//					memWrite32($address, $value);
//					break;
//				default:
//					break;
//			}
//		}
//		
//		
//		
//		/* END PROCESSOR Backend */
//		
//		/* Kinda normal stuff */
//	}
	
?>