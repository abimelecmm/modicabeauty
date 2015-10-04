<?php 
/* This is a simple script that I used to check how my website uses the diskspace at my provider: it creates two tables in your database */
if(!@include 'approve.php') die( "approve.php was not found!");

set_time_limit(240); /* 4 minutes: change this when needed */
$time1 = time();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product Diskspace Analysis</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
</head><body>';
print_menubar();
echo '<center><b><font size="+1">Overview of diskspace use</font></b></center>';
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'triple_diskspace( dirname VARCHAR(200) NOT NULL, filecount INT NOT NULL, dircount INT NOT NULL, totsize INT NOT NULL, PRIMARY KEY(dirname))';
$create_tbl = dbquery($create_table);
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'triple_imgspace( id_image INT NOT NULL, id_product INT, sourcename VARCHAR(200), productname VARCHAR(200), active INT, filecount INT, sourcesize INT, totsize INT, filenames VARCHAR(500), PRIMARY KEY(id_image))';
$create_tbl = dbquery($create_table);

$query= 'SELECT COUNT(*) AS dskcount FROM '._DB_PREFIX_.'triple_diskspace';
$result = dbquery($query);
$row = mysqli_fetch_array($result);
if(($row["dskcount"] == 0) || (isset($_GET["reset"])))
{
  if(isset($_GET["reset"]))
  { $create_table = 'TRUNCATE TABLE '._DB_PREFIX_.'triple_diskspace';
    $create_tbl = dbquery($create_table);
    $create_table = 'TRUNCATE TABLE '._DB_PREFIX_.'triple_imgspace';
    $create_tbl = dbquery($create_table);
  }
  $subdirs = array();
  $total_size = $total_files = $total_dirs = 0;
  $mydir = dir($triplepath);
  while(($file = $mydir->read()) !== false) {
    if(is_dir($triplepath.$file))
    { if(($file != ".") && ($file != ".."))
      { $subdirs[] = $file;
        $total_dirs++;
	  }
    }
    else
    { $total_size += filesize($triplepath.$file);
      $total_files++;
    }
  }

  $query = "REPLACE INTO "._DB_PREFIX_."triple_diskspace (dirname,filecount,dircount,totsize) VALUES ('root','".$total_files."','".$total_dirs."','".$total_size."')";
  $result = dbquery($query);
  echo "Root: ".$total_files." files - size: ".number_format($total_size)."<br>";

  foreach($subdirs AS $subdir)
  { // if($subdir == "img") continue;
    $total_files = $total_dirs = 0;
    $total_size = foldersize($triplepath.$subdir);
    $query = "REPLACE INTO "._DB_PREFIX_."triple_diskspace (dirname,filecount,dircount,totsize) VALUES ('".$subdir."','".$total_files."','".$total_dirs."','".$total_size."')";
    $result = dbquery($query);

    echo $subdir.": ".$total_files." files, ".$total_dirs." dirs, ".number_format($total_size)." bytes<br>";
  }

  echo (time() - $time1)." seconds passed";

  /* Now the IMG directory */

  $subdirs = array();
  $total_size = $total_files = $total_dirs = 0;
  $mydir = dir($triplepath."img/");
  while(($file = $mydir->read()) !== false) {
    if(is_dir($triplepath."img/".$file))
    { if(($file != ".") && ($file != ".."))
      { $subdirs[] = $file;
        $total_dirs++;
	  }
    }
    else
    { $total_size += filesize($triplepath."img/".$file);
      $total_files++;
    }
  }

  $query = "REPLACE INTO "._DB_PREFIX_."triple_diskspace (dirname,filecount,dircount,totsize) VALUES ('img-root','".$total_files."','".$total_dirs."','".$total_size."')";
  $result = dbquery($query);
  echo "<p>IMG-Root: ".$total_files." files - size: ".number_format($total_size)."<br>";

  foreach($subdirs AS $subdir)
  { // if($subdir == "p") continue;
    $total_files = $total_dirs = 0;
    $total_size = foldersize($triplepath."img/".$subdir);
    $query = "REPLACE INTO "._DB_PREFIX_."triple_diskspace (dirname,filecount,dircount,totsize) VALUES ('img-".$subdir."','".$total_files."','".$total_dirs."','".$total_size."')";
    $result = dbquery($query);

    echo "img-".$subdir.": ".$total_files." files, ".$total_dirs." dirs, ".number_format($total_size)." bytes<br>";
  }  
  echo "Main data finished: ".(time() - $time1)." seconds passed<br>";
}

$imageroot = $triplepath."img/p/";
$query = 'SELECT * FROM '._DB_PREFIX_.'triple_imgspace WHERE id_image="999999999"';
$result = dbquery($query);
if(mysqli_num_rows($result) == 0)
{ $total_files = $total_size = 0;
  analyze_folder($imageroot);
  $query = "INSERT INTO "._DB_PREFIX_."triple_imgspace SET id_image='999999999'"; /* insert an end marker */
  $result = dbquery($query);
  echo "Collecting image id's finished: ".(time() - $time1)." seconds passed<br>";
}
else echo "skipped collecting image id's<br>";

$query = 'SELECT * FROM '._DB_PREFIX_.'triple_imgspace WHERE id_image="999999999"';
$result = dbquery($query);
$row = mysqli_fetch_array($result);
if($row["id_product"] != "777777777")
{
  $query = "SELECT id_image, i.id_product, active, name FROM "._DB_PREFIX_."image i";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_shop ps ON i.id_product=ps.id_product";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl ON i.id_product=pl.id_product AND ps.id_shop=pl.id_shop";
  $query .= " WHERE ps.id_shop='1' AND pl.id_lang='6'";
  $result = dbquery($query);
  echo mysqli_num_rows($result)." rows<br>";
  while($row = mysqli_fetch_array($result))
  { $subquery = "UPDATE "._DB_PREFIX_."triple_imgspace SET id_product='".$row["id_product"]."', productname='".mysqli_real_escape_string($conn,$row["name"])."', active='".$row["active"]."' WHERE id_image = '".$row["id_image"]."'";
    $subresult = dbquery($subquery);
  }
  $query = "UPDATE "._DB_PREFIX_."triple_imgspace SET id_product='777777777' WHERE id_image = '999999999'";
  $result = dbquery($query);
  echo "Collecting product data finished: ".(time() - $time1)." seconds passed<br>";
}
else echo "skipped collecting product data<br>";

// Now start displaying the data
// First general table
$totalsize = $totfilecount = $totdircount = 0;
$query = 'SELECT * FROM '._DB_PREFIX_.'triple_diskspace';
$result = dbquery($query);
echo "<table border=1><tr><td>dirname</td><td>filecount</td><td>dircount</td><td>totsize</td></tr>";
while($row = mysqli_fetch_array($result))
{ echo "<tr><td>".$row["dirname"]."</td><td align=right>".$row["filecount"]."</td><td align=right>".$row["dircount"]."</td><td align=right>".number_format($row["totsize"])."</td></tr>";
  if(substr($row["dirname"],0,4) != "img-")
  { $totalsize += $row["totsize"];
    $totfilecount += $row["filecount"];
	$totdircount += $row["dircount"];
  }
}
echo "<tr><td></td></tr>";
echo "<tr><td>Total</td><td align=right>".$totfilecount."</td><td align=right>".$totdircount."</td><td align=right>".number_format($totalsize)."</td></tr>";
echo "</table><p>";

// now display images without product
// See here for a suggestion how to delete them: https://www.prestashop.com/forums/topic/383776-how-to-remove-images-that-are-not-anymore-exist-in-products/
$totalsize = $filecount = $imgcount = 0;
$query = 'SELECT * FROM '._DB_PREFIX_.'triple_imgspace WHERE id_product IS NULL';
$result = dbquery($query);
echo mysqli_num_rows($result)." rows<br>";
echo "<table border=1><tr><td colspan=4>Images without product</td></tr>";
echo "<tr><td>image</td><td>filecount</td><td>totsize</td><td>image</td></tr>";
while($row = mysqli_fetch_array($result))
{ echo "<tr><td>".$row["id_image"]."</td><td align=right>".$row["filecount"]."</td><td align=right>".number_format($row["totsize"])."</td><td>".get_product_image($row["id_image"],$row["id_image"])."</td></tr>";
  $totalsize += $row["totsize"];
  $filecount += $row["filecount"];
  $imgcount++;
}
echo "<tr><td>".$imgcount." images</td><td align=right>".$filecount."</td><td align=right>".number_format($totalsize)."</td><td></td></tr>";
echo "</table><p>";

// now look how many is occupied by inactive products
$query = 'SELECT count(*) AS imgcount, SUM(filecount) AS files, COUNT(DISTINCT id_product) AS prodcount, SUM(totsize) AS size FROM '._DB_PREFIX_.'triple_imgspace WHERE active=0 GROUP BY active LIMIT 50';
$result = dbquery($query);
echo "<table border=1><tr><td colspan=4>Images from inactive products</td></tr>";
echo "<tr><td>image count</td><td>product count</td><td>total files</td><td>total size</td></tr>";
$row = mysqli_fetch_array($result);
echo "<tr><td>".$row["imgcount"]."</td><td>".$row["prodcount"]."</td><td align=right>".$row["files"]."</td><td align=right>".number_format($row["size"])."</td></tr>";
echo "</table><p>";

// now show the products that consume most space
$query = 'SELECT id_product, productname, count(*) AS imgcount, SUM(filecount) AS files, SUM(totsize) AS size FROM '._DB_PREFIX_.'triple_imgspace WHERE active=0 GROUP BY id_product ORDER BY size DESC LIMIT 150';
$result = dbquery($query);
echo "<table border=1><tr><td colspan=5>Images from inactive products</td></tr>";
echo "<tr><td>id_product</td><td>productname</td><td>image count</td><td>total files</td><td>total size</td></tr>";
while($row = mysqli_fetch_array($result))
{ echo "<tr><td>".$row["id_product"]."</td><td>".$row["productname"]."</td><td align=right>".$row["imgcount"]."</td><td align=right>".number_format($row["files"])."</td><td align=right>".number_format($row["size"])."</td></tr>";
}
echo "</table><p>";


/* analyze picture folder */
function analyze_folder($path)
{ global $total_files, $total_size, $imageroot;
  $filecount = 0;
  $sourcesize = 0;
  $sourcename = "";
  $filenames = array();
  $totsize = 0;
  $files = scandir($path);
  $cleanPath = rtrim($path, '/'). '/';
  foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = analyze_folder($currentFile);
                $total_size += $size;
            }
            else {
				if($path == $imageroot) continue;
                $size = filesize($currentFile);
                $total_size += $size;
				$total_files++;
				if($t == "index.php") continue;
				$totsize += $size;
				$filecount++;
				$filenames[] = $t;
				if(!strpos($t,"-"))
				{ $sourcename = $t;
				  $sourcesize = $size;
				}
            }
        }   
    }
  $id_image = substr($sourcename,0,strpos($sourcename, "."));
  $query = "REPLACE INTO "._DB_PREFIX_."triple_imgspace (id_image, sourcename, filecount, sourcesize, totsize, filenames) ";
  $query .= "VALUES ('".$id_image."','".$sourcename."','".$filecount."','".$sourcesize."','".$totsize."','".implode(",",$filenames)."')";
  $result = dbquery($query);
  if($id_image != "")
  { echo $id_image.", ";
    if(!($id_image % 50)) echo "<br>";
  }
}
// id_image INT NOT NULL, id_product INT, sourcename VARCHAR(200), productname VARCHAR(200), active INT, filecount INT, sourcesize INT, totsize, filenames

function foldersize($path) {
    global $total_files, $total_dirs;
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
				$total_dirs++;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
				$total_files++;
            }
        }   
    }

    return $total_size;
}
   
?>