<?php

/** 
 * The MIT License
 *
 * Copyright 2018 Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * walkchain
 *
 * Walks through the blockchain and prints out the contents of each record.
 *
 * No verification of the blockchain validity is performed; this tool simply reads
 * each of the records from the blockchain ordered as they are stored on disk.
 *
 * Marty Anstey (https://marty.anstey.ca/)
 * August 2015
 *
 */
date_default_timezone_set('UTC');
define('_magic', 0xD5E8A97F);
define('_hashalg', 'sha256');
define('_hashlen', 32);
define('_fn', 'blockchain.dat');
if (!file_exists(_fn)) exit("Can't open "._fn);
$size = filesize(_fn);
$fp = fopen(_fn,'rb');
$height = 0;
while (ftell($fp) < $size) {
	$header = fread($fp, (13+_hashlen));
	$magic = unpack32($header,0);
	$version = ord($header[4]);
	$timestamp = unpack32($header,5);
	$prevhash = bin2hex(substr($header,9,_hashlen));
	$datalen = unpack32($header,-4);
	$data = fread($fp, $datalen);
	$hash = hash(_hashalg, $header.$data);
	print "height...... ".++$height."\n";
	print "magic....... ".dechex($magic)."\n";
	print "version..... ".$version."\n";
	print "timestamp... ".$timestamp." (".date("H:i:s m/d/Y",$timestamp).")\n";
	print "prevhash.... ".$prevhash."\n";
	print "blockhash... ".$hash."\n";
	print "datalen..... ".$datalen."\n";
	print "data........ ".wordwrap($data, 100)."\n\n";
}
fclose($fp);
function unpack32($data,$ofs) {
	return unpack('V', substr($data,$ofs,4))[1];
}