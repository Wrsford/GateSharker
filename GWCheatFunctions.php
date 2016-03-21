<?php
	// Cheat functions
	
	class CheatFunctions {
		/* use Oxford\Comma; */
		
		var $autoResetOffset = false;
		var $autoOffsetValue = 0x0;
		/* START Internal Helpers */
		
		public function setAutoOffsetResetValue($value) {
			$this->autoOffsetValue = $value;
		}
		
		public function setAutoOffsetReset($enable) {
			$this->autoResetOffset = $enable;
			$this->setAutoOffsetResetValue(0x0);
		}
		
		// Prepends zeros to a value until it is the
		//	given length.
	 	protected function padValueToLength($value, $length) {
			
			$filteredValue = $value;
			
			if (is_int($value)) {
				$filteredValue = dechex($value);
			}
		
			// Make sure it is a string
			$filteredValue = strtoupper("$filteredValue");
			
			if (strlen($filteredValue) > $length) {
				die("Attempted to pad a value '$filteredValue' to a length of: $length, which is less than the original length.");
			}
			
			while (strlen($filteredValue) < $length) {
				$filteredValue = "0" . $filteredValue;
			}
			return $filteredValue;
		}
		
		
		/* END Internal Helpers */
		
		/* START Memory writes */
		//	0XXXXXXX YYYYYYYY – 32bit write to [XXXXXXX + offset]
		public function memWrite32($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "0$paddedAddress $paddedImmediate";
		}
		
		//	1XXXXXXX 0000YYYY – 16bit write to [XXXXXXX + offset]
		public function memWrite16($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 4);
			
			return "1$paddedAddress 0000$paddedImmediate";
		}
		
		//	2XXXXXXX 000000YY – 8bit write to [XXXXXXX + offset]
		public function memWrite8($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 2);
			
			return "2$paddedAddress 000000$paddedImmediate";
		}
		/* END Memory writes */
		
		/* START Conditional 32bit codes */
		//	3XXXXXXX YYYYYYYY – Greater Than (YYYYYYYY > [XXXXXXX + offset])
		public function ifGreaterThan32($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "3$paddedAddress $paddedImmediate";
		}
		
		//	4XXXXXXX YYYYYYYY – Less Than (YYYYYYYY < [XXXXXXX + offset])
		public function ifLessThan32($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "4$paddedAddress $paddedImmediate";
		}
		
		//	5XXXXXXX YYYYYYYY – Equal To (YYYYYYYY == [XXXXXXX + offset])
		public function ifEqualTo32($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "5$paddedAddress $paddedImmediate";
		}
		
		//	6XXXXXXX YYYYYYYY – Not Equal To (YYYYYYYY != [XXXXXXX + offset])
		public function ifNotEqual32($address, $immediate) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "6$paddedAddress $paddedImmediate";
		}
		/* END Conditional 32bit codes */
		
		/* START Conditional 16bit deref + write codes */
		//	7XXXXXXX ZZZZYYYY – Greater Than
		public function ifGreaterThan16($address, $immediate, $mask = 0x0000) {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 4);
			$paddedMask = $this->padValueToLength($mask, 4);
			
			return "7$paddedAddress $paddedMask$paddedImmediate";
		}
		
		//	8XXXXXXX ZZZZYYYY – Less Than
		public function ifLessThan16($address, $immediate, $mask = "0000") {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 4);
			$paddedMask = $this->padValueToLength($mask, 4);
			
			return "8$paddedAddress $paddedMask$paddedImmediate";
		}
		
		//	9XXXXXXX ZZZZYYYY – Equal To
		public function ifEqualTo16($address, $immediate, $mask = "0000") {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 4);
			$paddedMask = $this->padValueToLength($mask, 4);
			
			return "9$paddedAddress $paddedMask$paddedImmediate";
		}
		
		//	AXXXXXXX ZZZZYYYY – Not Equal To
		public function ifNotEqual16($address, $immediate, $mask = "0000") {
			$paddedAddress = $this->padValueToLength($address, 7);
			$paddedImmediate = $this->padValueToLength($immediate, 4);
			$paddedMask = $this->padValueToLength($mask, 4);
			
			return "A$paddedAddress $paddedMask$paddedImmediate";
		}
		/* END Conditional 16bit deref + write codes */
		
		/* START Offset Codes */
		//	BXXXXXXX 00000000 – offset = *(xxx)
		public function setOffsetFromMemory($address) {
			$paddedAddress = $this->padValueToLength($address, 7);
			
			return "B$paddedAddress 00000000";
		}
		
		//	D3000000 XXXXXXXX – set offset to immediate value
		public function setOffset($immediate) {
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "D3000000 $paddedImmediate";
		}
		
		//	DC000000 XXXXXXXX – Adds an value to the current offset
		public function addToOffset($immediate) {
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "DC000000 $paddedImmediate";
		}
		/* END Offset Codes */
		
		/* START Loop Code */
		//	C0000000 YYYYYYYY – Sets the repeat value to ‘YYYYYYYY’
		public function setLoopCount($immediate) {
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "C0000000 $paddedImmediate";
		}
		
		//	D1000000 00000000 – Loop execute
		public function startLoop() {
			return "D1000000 00000000";
		}
		
		//	D0000000 00000000 – Terminator code
		public function terminateCodeBlock() {
			return "D0000000 00000000";
		}
		/* END Loop Code */
		
		/* START Data Register Codes */
		//	D4000000 XXXXXXXX – Adds XXXXXXXX to the data register
		public function addToData($immediate) {
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "D4000000 $paddedImmediate";
		}
		
		//	D5000000 XXXXXXXX – Sets the data register to XXXXXXXX
		public function setData($immediate) {
			$paddedImmediate = $this->padValueToLength($immediate, 8);
			
			return "D5000000 $paddedImmediate";
		}
		
		//	D6000000 XXXXXXXX – (32bit) [XXXXXXXX+offset] = data ; offset += 4
		public function writeDataToMemory32($address) {
			$paddedAddress = $this->padValueToLength($address, 8);
			
			return "D6000000 $paddedImmediate";
		}
		
		//	D7000000 XXXXXXXX – (16bit) [XXXXXXXX+offset] = data & 0xffff ; offset += 2
		public function writeDataToMemory16($address) {
			$paddedAddress = $this->padValueToLength($address, 8);
			
			return "D7000000 $paddedAddress";
		}
		
		//	D8000000 XXXXXXXX – (8bit) [XXXXXXXX+offset] = data & 0xff ; offset++
		public function writeDataToMemory8($address) {
			$paddedAddress = $this->padValueToLength($address, 8);
			
			return "D8000000 $paddedAddress";
		}
		
		//	D9000000 XXXXXXXX – (32bit) sets data to [XXXXXXXX+offset]
		public function setDataFromMemory32($address) {
			$paddedAddress = $this->padValueToLength($address, 8);
			
			return "D9000000 $paddedAddress";
		}
		
		//	DA000000 XXXXXXXX – (16bit) sets data to [XXXXXXXX+offset] & 0xffff
		public function setDataFromMemory16($address) {
			$paddedAddress = $this->padValueToLength($address, 8);
			
			return "DA000000 $paddedAddress";
		}
		
		//	DB000000 XXXXXXXX – (8bit) sets data to [XXXXXXXX+offset] & 0xff
		public function setDataFromMemory8($address) {
			$paddedAddress = $this->padValueToLength($address, 8);
			
			return "DB000000 $paddedAddress";
		}
		/* END Data Register Codes */
		
		/* START Special Codes */
		
		// DD000000 XXXXXXXX – if KEYPAD has value XXXXXXXX execute next block
		public function ifButtonIsPressed($keycode) {
			$paddedKeycode = $this->padValueToLength($keycode, 8);
			
			return "DD000000 $paddedKeycode";
		}
		/* END Special Codes */
	}
	
	/* EASY ACCESS */
	
	$cheatFunctions = new CheatFunctions();
	
	function priv_autoOffsetCheck() {
		global $cheatFunctions;
		if ($cheatFunctions->autoResetOffset) {
			setOffset($cheatFunctions->autoOffsetValue);
		}
	}
	
	/* START Memory writes */
	//	0XXXXXXX YYYYYYYY – 32bit write to [XXXXXXX + offset]
	function memWrite32($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->memWrite32($address, $immediate) . PHP_EOL;
	}
	
	//	1XXXXXXX 0000YYYY – 16bit write to [XXXXXXX + offset]
	function memWrite16($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->memWrite16($address, $immediate) . PHP_EOL;
	}
	
	//	2XXXXXXX 000000YY – 8bit write to [XXXXXXX + offset]
	function memWrite8($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->memWrite8($address, $immediate) . PHP_EOL;
	}
	/* END Memory writes */
	
	/* START Conditional 32bit codes */
	//	3XXXXXXX YYYYYYYY – Greater Than (YYYYYYYY > [XXXXXXX + offset])
	function ifGreaterThan32($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->ifGreaterThan32($address, $immediate) . PHP_EOL;
	}
	
	//	4XXXXXXX YYYYYYYY – Less Than (YYYYYYYY < [XXXXXXX + offset])
	function ifLessThan32($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->ifLessThan32($address, $immediate) . PHP_EOL;
	}
	
	//	5XXXXXXX YYYYYYYY – Equal To (YYYYYYYY == [XXXXXXX + offset])
	function ifEqualTo32($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->ifEqualTo32($address, $immediate) . PHP_EOL;
	}
	
	//	6XXXXXXX YYYYYYYY – Not Equal To (YYYYYYYY != [XXXXXXX + offset])
	function ifNotEqual32($address, $immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->ifNotEqual32($address, $immediate) . PHP_EOL;
	}
	/* END Conditional 32bit codes */
	
	/* START Conditional 16bit deref + write codes */
	//	7XXXXXXX ZZZZYYYY – Greater Than
	function ifGreaterThan16($address, $immediate, $mask = 0x0000) {
		global $cheatFunctions;
		echo $cheatFunctions->ifGreaterThan16($address, $immediate, $mask) . PHP_EOL;
	}
	
	//	8XXXXXXX ZZZZYYYY – Less Than
	function ifLessThan16($address, $immediate, $mask = "0000") {
		global $cheatFunctions;
		echo $cheatFunctions->ifLessThan16($address, $immediate, $mask) . PHP_EOL;
	}
	
	//	9XXXXXXX ZZZZYYYY – Equal To
	function ifEqualTo16($address, $immediate, $mask = "0000") {
		global $cheatFunctions;
		echo $cheatFunctions->ifEqualTo16($address, $immediate, $mask) . PHP_EOL;
	}
	
	//	AXXXXXXX ZZZZYYYY – Not Equal To
	function ifNotEqual16($address, $immediate, $mask = "0000") {
		global $cheatFunctions;
		echo $cheatFunctions->ifNotEqual16($address, $immediate, $mask) . PHP_EOL;
	}
	/* END Conditional 16bit deref + write codes */
	
	/* START Offset Codes */
	//	BXXXXXXX 00000000 – offset = *(xxx)
	function setOffsetFromMemory($address) {
		global $cheatFunctions;
		echo $cheatFunctions->setOffsetFromMemory($address) . PHP_EOL;
	}
	
	//	D3000000 XXXXXXXX – set offset to immediate value
	function setOffset($immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->setOffset($immediate) . PHP_EOL;
	}
	
	//	DC000000 XXXXXXXX – Adds an value to the current offset
	function addToOffset($immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->addToOffset($immediate) . PHP_EOL;
	}
	/* END Offset Codes */
	
	/* START Loop Code */
	//	C0000000 YYYYYYYY – Sets the repeat value to ‘YYYYYYYY’
	function setLoopCount($immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->setLoopCount($immediate) . PHP_EOL;
	}
	
	//	D1000000 00000000 – Loop execute
	function startLoop() {
		global $cheatFunctions;
		echo $cheatFunctions->startLoop() . PHP_EOL;
	}
	
	//	D0000000 00000000 – Terminator code
	function terminateCodeBlock() {
		global $cheatFunctions;
		echo $cheatFunctions->terminateCodeBlock() . PHP_EOL;
	}
	/* END Loop Code */
	
	/* START Data Register Codes */
	//	D4000000 XXXXXXXX – Adds XXXXXXXX to the data register
	function addToData($immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->addToData($immediate) . PHP_EOL;
	}
	
	//	D5000000 XXXXXXXX – Sets the data register to XXXXXXXX
	function setData($immediate) {
		global $cheatFunctions;
		echo $cheatFunctions->setData($immediate) . PHP_EOL;
	}
	
	//	D6000000 XXXXXXXX – (32bit) [XXXXXXXX+offset] = data ; offset += 4
	function writeDataToMemory32($address) {
		global $cheatFunctions;
		echo $cheatFunctions->writeDataToMemory32($address) . PHP_EOL;
	}
	
	//	D7000000 XXXXXXXX – (16bit) [XXXXXXXX+offset] = data & 0xffff ; offset += 2
	function writeDataToMemory16($address) {
		global $cheatFunctions;
		echo $cheatFunctions->writeDataToMemory16($address) . PHP_EOL;
	}
	
	//	D8000000 XXXXXXXX – (8bit) [XXXXXXXX+offset] = data & 0xff ; offset++
	function writeDataToMemory8($address) {
		global $cheatFunctions;
		echo $cheatFunctions->writeDataToMemory8($address) . PHP_EOL;
	}
	
	//	D9000000 XXXXXXXX – (32bit) sets data to [XXXXXXXX+offset]
	function setDataFromMemory32($address) {
		global $cheatFunctions;
		echo $cheatFunctions->setDataFromMemory32($address) . PHP_EOL;
	}
	
	//	DA000000 XXXXXXXX – (16bit) sets data to [XXXXXXXX+offset] & 0xffff
	function setDataFromMemory16($address) {
		global $cheatFunctions;
		echo $cheatFunctions->setDataFromMemory16($address) . PHP_EOL;
	}
	
	//	DB000000 XXXXXXXX – (8bit) sets data to [XXXXXXXX+offset] & 0xff
	function setDataFromMemory8($address) {
		global $cheatFunctions;
		echo $cheatFunctions->setDataFromMemory8($address) . PHP_EOL;
	}
	/* END Data Register Codes */
	
	/* START Special Codes */
	
	// DD000000 XXXXXXXX – if KEYPAD has value XXXXXXXX execute next block
	function ifButtonIsPressed($keycode) {
		global $cheatFunctions;
		echo $cheatFunctions->ifButtonIsPressed($keycode) . PHP_EOL;
	}
	/* END Special Codes */
	
	/**** START Additional Extensions ****/
	
	// Subtraction, strings will be treated as hex.
	// So pass in an int to avoid problems
	function subtractFromData($immediate) {
		$filteredImmediate = $immediate;
		if (!is_int($filteredImmediate)) {
			$filteredImmediate = hexdec($immediate);
		}
		
		$filteredImmediate = dechex((0 - $filteredImmediate) & 0xffffffff);
		
		addToData($filteredImmediate);
		/* I was thinking about the following:
			4 - 5 = -1
			Soooo let's show my work...
			FYI: I like to divide 32bit hexes with a pipe for readability,
				all unique hexes will start with '0x'
			0x0 - 0x5 = 0xffff|ffff - 0x4
			0xffff|ffff - 0x4 = 0xffff|fffb
			0x4 + 0xffff|fffb = 0xffff|ffff
			0xffff|ffff = -1
			Checks out & overflows properly :)
		//*/
	}
	
	function subtractFromOffset($immediate) {
		$filteredImmediate = $immediate;
		if (!is_int($filteredImmediate)) {
			$filteredImmediate = hexdec($immediate);
		}
		
		$filteredImmediate = dechex((0 - $filteredImmediate) & 0xffffffff);
		
		addToOffset($filteredImmediate);
	}
	
	function endLoop() {
		terminateCodeBlock();
	}
	
	function doLoop($count, $code) {
		setLoopCount($count);
		{
			$code();
		}
		endLoop();
		startLoop();
	}
	
	/**** END Additional Extensions ****/
?>