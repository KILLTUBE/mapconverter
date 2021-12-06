<?php
	/*
		php cod2_to_quake3.php mp_beginning_cod2.map mp_beginning_quake3.map
	*/
	if ($argc != 3)
		die("need exactly two .map-file arguments, input output");
	$mapname_in = $argv[1]; // argv[0] is thisscriptname.php
	$mapname_out = $argv[2];
	//$mapname_dotparts = explode(".", $mapname);
	//$mapname_extension = end($mapname_dotparts);
	//if ($mapname_extension != "map")
	//	die("extension needs to be .map");
	//array_pop($mapname_dotparts); // remove "map"
	//echo implode(".", $mapname_dotparts);
	
	$map = file_get_contents($mapname_in);
	
	$map = str_replace("iwmap 4", "", $map);
	$map = str_replace("mp_dm_spawn", "info_player_deathmatch", $map);
	$map = str_replace("lightmap_gray 16384 16384 0 0", "", $map);
	$map = str_replace('"classname" "script_brushmodel"', '"classname" "func_plat"', $map);
	
	$map = str_replace("mtl_caen_road_cobblestone_01",       "kungtile/metalfloor", $map);
	//$map = str_replace("caulk",                            "metalfloor"         , $map);
	$map = str_replace("mtl_caen_plaster_int2f",             "concrete/white"     , $map);
	$map = str_replace("toujane_plasterwall_white",          "concrete/white"     , $map);
	$map = str_replace("detail_plasterwall_top_s",           "concrete/desertish" , $map);
	$map = str_replace("mtl_white_window_a",                 "window/1"           , $map);
	$map = str_replace("mtl_duhoc_light_dirt",               "other/forest3"      , $map);
	$map = str_replace("duhoc_asphalt_road",                 "concrete/ceiling2"  , $map);
	$map = str_replace("clip_water_player",                  "liquids/water"      , $map);
	$map = str_replace("clip_water",                         "liquids/water"      , $map);
	$map = str_replace("ladder",                             "tools/ladder"       , $map);
	$map = str_replace("d_duhoc_sky_reflect",                "skybox"             , $map);
	$map = str_replace("mtl_caen_trim_ext_worn_wood_01_01",  "wood/a"             , $map);
	$map = str_replace("dawnville2_wood_boards01",           "wood/a"             , $map);
	
	$map = str_replace("\r\n", "\n", $map); // microsoft, we are writing the year 2017... let it die
	
	
	
	$lines = explode("\n", $map);
	
	foreach ($lines as &$line) {
		// detect a brush plane, " ("
		if (strlen($line) >= 2 && $line[0] == " " && $line[1] == "(")
		{
			$parts = explode(" ", $line);
			
			// lines look like: " ( -7040 4736 -1024 ) ( -8448 4736 -1024 ) ( -8448 -7168 -1024 ) killtube/textures/whiteconcrete 256 256 0 0 0 0  0 0"
			// so parts will be; 
			//var_dump($parts);
			if (count($parts) != 26)
				die("NOT 26");
			
			/*

  [15]=>
  string(1) ")"
  [16]=>
  string(31) "killtube/textures/whiteconcrete"
  [17]=>
  string(3) "256"
  [18]=>
  string(3) "256"
  [19]=>
  string(4) "-384"
  [20]=>
  string(1) "0"
  [21]=>
  string(1) "0"
  [22]=>
  string(1) "0"
  [23]=>
  string(0) ""
  [24]=>
  string(1) "0"
  [25]=>
  string(1) "0"			
			*/
			
			$firstpart = "";
			for ($i=0; $i<=16; $i++)
				$firstpart .= $parts[$i] . " ";
			
			
			$meh0 = (float)$parts[17];
			$meh1 = (float)$parts[18];
			$meh2 = (float)$parts[19];
			$meh3 = (float)$parts[20];
			$meh4 = (float)$parts[21];
			$meh5 = (float)$parts[22];
			$meh6 = (float)$parts[23];
			$meh7 = (float)$parts[24];
			$meh8 = (float)$parts[25];
			
			// gtkradiant be like: 0 0 0 0.1000000015 0.1000000015 0 0 0
			// cod2radiant be like: 128 128 0 0 0 0 0 0
			
			// meh0, meh1 = hstretch, vstretch, needs to be divided by 512
			
			$hs = $meh0 / 512;
			$vs = $meh1 / 512;
			
			// dont give a fuck about other numbers right now
			//$line = "$firstpart $meh0 $meh1 $meh2 $meh3 $meh4 $meh5 $meh6 $meh7 $meh8";
			$line = "$firstpart 0 0 0 $hs $vs 0 0 0";
		}
		
	}
	
	$map = implode("\n", $lines);
	
	file_put_contents($mapname_out, $map);
	
?>