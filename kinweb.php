<?php

/*

	** tools: kinWeb V.1.0 **

	code: php (CLI)
	author: Ms.ambari
	date: 2018-12-29
	YouTube: Ms.ambari
	Github: https://github.com/Ranginang67
	---
	follow me on github and youtube to support.
	contact me on telegram: https://t.me/Msambari

*/

class Naon {
	var $results = []; // results of dorking url
	var $expl = [];
	public static $color = [ // set color
		"red" => "\033[31m",
		"gren" => "\033[32m",
		"reset" => "\033[0m"];
	public static $usg = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0";
	public function box($cl,$thesimbol) {
		$col = self::$color["reset"]."[".self::$color[$cl].$thesimbol.self::$color["reset"]."] ";
		return $col;
	}
	public function dorking($dork,$max_page=5) {
		echo self::box("gren","+")."Wait, dorking ...\n";
		for ($thecounterofpage=1; $thecounterofpage <= $max_page; $thecounterofpage++) { 
			$searchurl = "http://www1.search-results.com/web?ts=1545914791433&q=".$dork."&tpr=10&page=".$thecounterofpage."&ots=1545914823388";
			$content = file_get_contents($searchurl);
			@preg_match_all("/<a class=\"algo-title.\".href=\"(.*)\"/", $content, $hasil);
			for ($i=0; $i < count($hasil[1]); $i++) { 
				$this->results[] = $hasil[1][$i];
			}
		}
		$gege = array_unique($this->results);
		echo self::box("gren","+")."Scanning ".self::$color["gren"].count($gege).self::$color["reset"]." Website ...\n";
		for ($kool=0; $kool < count($gege); $kool++) {
			if(!empty($gege[$kool])) {
				$thetargets = $gege[$kool]."'";
				$chsq = curl_init($thetargets);
				$opsion = array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_USERAGENT => self::$usg,
					CURLOPT_SSL_VERIFYPEER => 1,
					CURLOPT_HEADER => 0);
				@curl_setopt_array($chsq, $opsion);
				$ten = curl_exec($chsq);
				if (@preg_match_all("/You have an error in your SQL syntax|error in your SQL syntax/", $ten, $cod)) {
					$heks = str_replace("'", "", $thetargets);
					$fileopenvuln = fopen("vuln_sql.txt", "a+") or die("...");
					$maskn = $heks."\n";
					fwrite($fileopenvuln, $maskn);
					fclose($fileopenvuln);
					echo self::box("gren"," VULN ").self::$color["red"]."=> ".self::$color["reset"].$heks."\n";
				}
			}
		}
	}
	public function webp() {
		if (file_exists("lib/path.txt")) {
			$fileopen = file_get_contents("lib/path.txt");
			$path = explode(",", $fileopen);
			mn:
			echo self::box("gren","?")."Website/URL : ";
			$sites = trim(fgets(STDIN));
			if (!empty($sites)) {
				for ($pathofexploit=0; $pathofexploit < count($path); $pathofexploit++) { 
					$targets = $sites."/".$path[$pathofexploit];
					$ch = curl_init($targets);
					$curloption = array(
						CURLOPT_USERAGENT => self::$usg,
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_HEADER => 0,
						CURLOPT_SSL_VERIFYPEER => 1);
					@curl_setopt_array($ch, $curloption);
					@curl_exec($ch);
					$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if ($response_code < 200 or $response_code > 300) { /* pass */ }
					else { echo self::box("gren"," FOUND ").self::$color["red"]."=> ".self::$color["reset"].$targets."\n"; }
				}
			}
			else { goto mn; }
		}
		else { echo self::box("red","!")."No such file: 'lib/path.txt'\n"; }
	}
	public function backdorsc($filepath) {
		echo self::box("gren","?")."Website/URL: ";
		$webs = trim(fgets(STDIN));
		$fileop = file_get_contents($filepath);
		$tor = explode(",", $fileop);
		echo self::box("gren","+")."Scanning ...\n";
		for ($pathback=0; $pathback < count($tor); $pathback++) { 
			$tr = $webs."/".$tor[$pathback];
			$chr = curl_init($tr);
			$opsi = array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_USERAGENT => self::$usg,
				CURLOPT_SSL_VERIFYPEER => 1,
				CURLOPT_HEADER => 0);
			@curl_setopt_array($chr, $opsi);
			@curl_exec($chr);
			$responcode = curl_getinfo($chr, CURLINFO_HTTP_CODE);
			if ($responcode < 200 or $responcode > 300){ /* pass */ }
			else{ echo self::box("gren"," FOUND ").self::$color["red"]."=> ".self::$color["reset"].$tr."\n"; }
		}
	}
	public function cmsdt() {
		echo self::box("gren","?")."Site: ";
		$stst = trim(fgets(STDIN));
		if(substr($stst, -1) != "/"){ $stst = $stst."/"; }
		$ch = curl_init($stst);
		$op = array(
			CURLOPT_USERAGENT => self::$usg,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_SSL_VERIFYPEER => 1);
		@curl_setopt_array($ch, $op);
		$con = curl_exec($ch);
		if(@preg_match_all("/com_content|content=\"[a-zA-Z]oomla!\"|jdownloads/", $con, $hs)) {
			echo self::box("gren","+")."Joomla detected !\n"; }
		elseif (@preg_match_all("/wp-content\/upload|content=\"WordP[\D]*(.*)\"|wp-include|wp-content|type\".content=\"website\"/", $con, $hs)) {
			echo self::box("gren","+")."WordPress detected !\n"; }
		elseif (@preg_match_all("/sites\/all|Drupal|drupal.org|tent=\"[a-zA-Z]*rupal/", $con, $hs)) {
			echo self::box("gren","+")."Drupal detected !\n";
		}else {
			echo self::box("red","!")."CMS not detected!\n"; }
	}
	public function rbtext() {
		echo self::box("gren","?")."Site: ";
		$webst = trim(fgets(STDIN));
		$targs = $webst."/robots.txt";
		$cah = curl_init($targs);
		$opsion = array(
			CURLOPT_USERAGENT => self::$usg,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_SSL_VERIFYPEER => 1);
		@curl_setopt_array($cah, $opsion);
		$ges = curl_exec($cah);
		switch ($cdd = curl_getinfo($cah, CURLINFO_HTTP_CODE)) {
			case 200:
				echo $ges."\n";
				break;
			default:
				echo "Not found\n";
		}
	}
}
class MainTool extends Naon {
	public function banner() {
		if (is_dir("/usr/bin/")){ @system("resize -s 33 61 && reset"); }
		else{ @system("clear"); }
		echo '
	
                         __________
                      .~#########%%;~.
                     /############%%;`\
                    /######/~\/~\%%;,;,\
                   |#######\    /;;;;.,.|
                   |#########\/%;;;;;.,.|
          XX       |##/~~\####%;;;/~~\;,|       XX
        XX..X      |#|  o  \##%;/  o  |.|      X..XX
      XX.....X     |##\____/##%;\____/.,|     X.....XX
 XXXXX.....XX      \#########/\;;;;;;,, /      XX.....XXXXX
X |......XX%,.@      \######/%;\;;;;, /      @#%,XX......| X
X |.....X  @#%,.@     |######%%;;;;,.|     @#%,.@  X.....| X
X  \...X     @#%,.@   |# # # % ; ; ;,|   @#%,.@     X.../  X
 X# \.X        @#%,.@                  @#%,.@        X./  #
  ##  X          @#%,.@              @#%,.@          X   #
  "# #X            @#%,.@          @#%,.@            X ##
   `###X             @#%,.@      @#%,.@             ####\'
    \' ###              @#%.,@  @#%,.@              ###`"
      ";"                @#%.@#%,.@                ;"`\'  
      \'                    @#%,.@                    
                         @#%,.@  @@                 
                          @@@  @@@ 
';	}
	public function finish(){ echo "Press ".self::box("gren"," ENTER "); trim(fgets(STDIN)); }
	public function mean() {
		self::banner();
		si:
		echo "
\t".self::box("gren","1")."website directory/path scanning
\t".self::box("gren","2")."website shell backdoor path scanning
\t".self::box("gren","3")."Detect CMS on the website
\t".self::box("gren","4")."dorking sql injection vulnerability scan
\t".self::box("gren","5")."get robots.txt file from the website
\t".self::box("gren","6")."about us/kinWeb
\t".self::box("red","x")."exit this tool\n
".self::box("gren","?")."kinWeb-menu: ";
		$pil = trim(fgets(STDIN));
		if ($pil == 1) { self::webp(); self::finish(); goto si;}
		elseif($pil == 2) { self::backdorsc("lib/backdoor.txt"); self::finish(); goto si; }
		elseif($pil == 3) { self::cmsdt(); self::finish(); goto si; }
		elseif($pil == 4) { echo self::box("gren","+")."Dork: "; $drk = trim(fgets(STDIN)); self::dorking($drk); self::finish(); goto si; }
		elseif($pil == 5) { self::rbtext(); self::finish(); goto si; }
		elseif($pil == 6) {
			echo "
About:
---
Author\t: Ms.ambari
Date\t: 2018-12-29
Github\t: https://github.com/Ranginang67
YouTube\t: Ms.ambari\n\n"; self::finish(); goto si;
		}
		elseif($pil == "x") { exit(); }
		else{ goto si; }
	}
}
$obj = new MainTool;
$obj->mean();
?>
