<?php
	// MH4U Cheats
	include_once "GWCheatGlobal.php";
	
	/*
	Notes:
		$staminaOnline = "301ad334";
		
	Thoughts:
		Check the 80 item section for 16bit numbers that check out to be item codes.
			Make sure to account for item maximums.
		
	*/
	
	$onlineInvStart = 0x892CA3E;
	$invStart = 0x8375EF0;
	$questInvStart = 0x892C9DE;
	$questInvStart = 0x892C63C-2;
	$inventoryAddresses = array($invStart);
	
	// Not used
	function ifInTown($code) {
		setOffset(0);
		ifLessThan32(0x81C7CD0, 0x8800000);
		{
			
			$code();
			
		}
		terminateCodeBlock();
	}
	
	function ifInQuest($code) {
		setOffset(0);
		ifLessThan32(0x81C7CD0, 0x8800000);
		{
			ifGreaterThan32(0x81C7CD0, 0x8D00000);
			{
				
				$code();
				
			}
		}
		echo "D2000000 00000000" . PHP_EOL;
	}
	
	
	class FirstItemInfiniteCheat extends Cheat {
		var $name = "All Items Infinite";
		public function payload() {
			global $inventoryAddresses;
			setOffset(0);
			
			foreach ($inventoryAddresses as $invAddr) {
				$inventorySlots = 8*5;
				doLoop($inventorySlots, function () use ($invAddr) {
					ifNotEqual16($invAddr, 0);
					{
						memWrite16($invAddr + 2, 0x1FF);
					}
					terminateCodeBlock();
					
					addToOffset(4);
					
				});
				//memWrite16($invAddr+2, 0x01FF);
			}
		}
	}
	
	class CycleFirstItemQuest extends Cheat {
		var $name = "Y+L/R Cycle Item (Quest)";
		public function payload() {
			$invStart = 0x108E;
			$invAddr = 0x108E;
			$invPtr = 0x081C7CD0;
			//$invOff = 0x106E+0x120;
			
			$rItr = $invStart-2;
			$lItr = $rItr-2;
			$resetMark = $lItr-2;
			
			setOffset(0);
			setOffsetFromMemory($invPtr);
			
			memWrite8($resetMark, 1);
			
			ifButtonIsPressed(KEYPAD_L + KEYPAD_Y);
				// Reset R iterator
				memWrite16($rItr, 0);
				
				ifEqualTo16($lItr, 0);
					// Mark the L button as being pressed
					memWrite16($lItr, 1);
					
					setDataFromMemory16($invAddr);
					// Subtract from the first item code in the inventory
					subtractFromData(1);
					// Write modified value to RAM
					writeDataToMemory16($invAddr);
					setOffset(0);
					setOffsetFromMemory($invPtr);
				terminateCodeBlock();
			
				memWrite16($resetMark, 0);
				
			terminateCodeBlock();
			
			ifButtonIsPressed(KEYPAD_R + KEYPAD_Y);
			{
				// Reset L iterator
					memWrite16($lItr, 0);
					
					ifEqualTo16($rItr, 0);
					{
						// Mark the R button as being pressed
						memWrite16($rItr, 1);
						
							// Load the first item code
							setDataFromMemory16($invAddr);
							// Add to the first item code in the inventory
							addToData(1);
							// Write modified value to RAM
							writeDataToMemory16($invAddr);
							
							setOffset(0);
							setOffsetFromMemory($invPtr);
					}
					terminateCodeBlock();	
					
					memWrite16($resetMark, 0);
			}
			terminateCodeBlock();
			
			
			ifEqualTo16($resetMark, 1);
			{
				memWrite16($lItr, 0);
				memWrite16($rItr, 0);
			}
			terminateCodeBlock();
		}
		
	}
	
	class CycleFirstItem extends Cheat {
		var $name = "Y+L/R Cycle First Item";
		public function payload() {
			
			
			
				global $inventoryAddresses, $invStart;
				
				// These are for preventing super fast cycling.
				$rItr = $invStart-2;
				$lItr = $rItr-2;
				$resetMark = $lItr-2;
				
				setOffset(0);
				
				memWrite8($resetMark, 1);
				
				ifButtonIsPressed(KEYPAD_L + KEYPAD_Y);
					// Reset R iterator
					memWrite16($rItr, 0);
					
					ifEqualTo16($lItr, 0);
						// Mark the L button as being pressed
						memWrite16($lItr, 1);
						
						foreach ($inventoryAddresses as $invAddr) {
							// Load the first item code
							setDataFromMemory16($invAddr);
							// Subtract from the first item code in the inventory
							subtractFromData(1);
							// Write modified value to RAM
							writeDataToMemory16($invAddr);
							setOffset(0);
						}
					terminateCodeBlock();
				
					memWrite16($resetMark, 0);
					{
						// Signature
						// 0x57 0x72 0x73 0x66 0x6f 0x72 0x64
						setOffset(0x57727366);
						setOffset(0x6f726400);
						setOffset(0xDEADFACE);
						setOffset(0);
					}
				terminateCodeBlock();
				
				
				ifButtonIsPressed(KEYPAD_R + KEYPAD_Y);
				{
					// Reset L iterator
						memWrite16($lItr, 0);
						
						ifEqualTo16($rItr, 0);
						{
							// Mark the R button as being pressed
							memWrite16($rItr, 1);
							
							foreach ($inventoryAddresses as $invAddr) {
								// Load the first item code
								setDataFromMemory16($invAddr);
								// Add to the first item code in the inventory
								addToData(1);
								// Write modified value to RAM
								writeDataToMemory16($invAddr);
								setOffset(0);
							}
						}
						terminateCodeBlock();	
						
						memWrite16($resetMark, 0);
				}
				terminateCodeBlock();
				
				
				ifEqualTo16($resetMark, 1);
				{
					memWrite16($lItr, 0);
					memWrite16($rItr, 0);
				}
				terminateCodeBlock();
				
				ifInQuest(function() {
					$inQuest = new CycleFirstItemQuest();
					$inQuest->payload();
				});
			
		}
	}
	
	// MarcusCarter's code
	class InfiniteStaminaCheat extends Cheat {
		var $name = "Infinite Stamina";
		public function payload() {
			echo "481C7CD0 08800000
381C7CD0 08D00000
B81C7CD0 00000000
0000118C 44610000
00001190 44610000
D2000000 00000000" . PHP_EOL;
		}
	}
	
	// MarcusCarter's code
	class PlayerAttack extends Cheat {
		var $name = "Y+Up/Down Attack Mult";
		public function payload() {
			ifButtonIsPressed(DPAD_UP + KEYPAD_Y);
				memWrite32("DEC7C0", "E92D4006");
				memWrite32("DEC7C4", "EBD431D2");
				memWrite32("DEC7C8", "E3A01064");
				memWrite32("DEC7CC", "E0000190");
				memWrite32("DEC7D0", "E3A02C7D");
				memWrite32("DEC7D4", "E1500002");
				memWrite32("DEC7D8", "A1A00002");
				memWrite32("DEC7DC", "E8BD8006");
				memWrite32("AF8B60", "EB0BCF16");
				memWrite32("B1EA78", "EB0B3750");
			terminateCodeBlock();
			
			ifButtonIsPressed(DPAD_DOWN + KEYPAD_Y);
				memWrite32("DEC7C8", "E3A01001");
			terminateCodeBlock();
			
		}
	}
	
	// MarcusCarter's code
	class AutotrackerEnabled extends Cheat {
		var $name = "Autotracker Enabled";
		public function payload() {
			setOffset(0);
			memWrite32(0xB964E8, 0xE3A00001);
		}
	}
	
	// DumpRom went poorly
	class DumpRom extends Cheat {
		var $name = "Dump Rom";
		public function payload() {
			$marker = 0x8375EF0-0x10;
			$dumpSize = 0xec0000;
			setOffset(0x0);
			
			ifNotEqual32($marker, 0xdeadface);
			{
				memWrite32($marker, 0xdeadface);
				doLoop($dumpSize, function() {
					
					$dumpLocation = 0x30000000;
					setDataFromMemory8(0x0);
					writeDataToMemory8($dumpLocation);
				});
				
			}
			terminateCodeBlock();
			
		}
	}
	
	// MarcusCarter's code
	class SpeedHack extends Cheat {
		var $name = "(SELECT+L/R)Player speed x2/x1";
		public function payload() {
			echo 'DD000000 00000204
D3000000 00000000
00DEC740 E5950E30
00DEC744 E92D4002
00DEC748 E59F1004
00DEC74C E580139C
00DEC750 E8BD8002
00DEC754 40000000
00DEC758 E92D4002
00DEC75C E51F1010
00DEC760 E580139C
00DEC764 EDD00AE7' . PHP_EOL;
		}
	}
	
	// MarcusCarter's code
	class DefenceHack extends Cheat {
		var $name = "X+Up/Down Defense Multiplier";
		public function payload() {
			echo "DD000000 00000440
D3000000 00000000
00DEC800 E92D4006
00DEC804 EBD431C2
00DEC808 E3A01064
00DEC80C E0201091
00DEC810 E2400001
00DEC814 E3A02C7D
00DEC818 E2422001
00DEC81C E1500002
00DEC820 A1A00002
00DEC824 E8BD8006
00AF8044 EB0BD1ED
00B1EEA0 EB0B3656
D0000000 00000000
DD000000 00000480
00DEC808 E3A01001
D0000000 00000000" . PHP_EOL;
		}
	}
	
	
	function printAllCheats() {
		$allCheats = array(
			new FirstItemInfiniteCheat(),
			new CycleFirstItem(),
			new InfiniteStaminaCheat(),
			new PlayerAttack(),
			new AutotrackerEnabled(),
			new DefenceHack()
			//new SpeedHack()
			//new DumpRom()
		);
		
		foreach ($allCheats as $thisCheat) {
			$thisCheat->execute();
		}
	}
	
	printAllCheats();
	
?>