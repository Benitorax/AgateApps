<?php $time = microtime(true);$temp_time = $time;


echo 'Conversion Corahn-Rin'."\n";
$arglocal = isset($argv[1]) ? $argv[1] : 'local';
$argverbose = isset($argv[2]) ? ($argv[2] === 'verbose') : false;
if ($arglocal === 'local') { exec('chcp 65001'); }
ini_set('cli_server.color', true);
ini_set('cli_server.color', 1);
$colors = new Colors();
showtime($temp_time, 'Conversion des tables de l\'ancienne vers la nouvelle version');

$global_time = microtime(true);
$total_times = array();
$total_msgs = array();

include '_classDatabase.php';

function showtime(&$temp_time,$b){
	global $total_times;
//	global $colors;
//	global $total_msgs;
	$temp_time=microtime(true)-$temp_time;
	$temp_time*=1000;
	$total_times[]=$temp_time;
	$b = trim($b, " \t\n\r\0\x0B");
	$b = str_replace(array("\r","\n"), array('',''), $b);
	$b = str_replace("\n", '', $b);
	$str = "\t\n\r\0\x0B";
	$str = str_split($str);
	$b = str_replace($str, array_fill(0,5,''), $b);
//	 print($colors->getColoredString(number_format($temp_time, 4, ',', ' ')."\t\t".$b, "blue"));
	$numb = number_format($temp_time, 0, ',', ' ');
	$numb = substr($numb, 0, 6);
	$numb = str_pad($numb, 10, ' ', STR_PAD_LEFT);
	$b = "[".$numb."ms]\t".$b;
	$b = trim($b);
	$b = trim($b, " \t\n\r\0\x0B");
	$b = str_replace(array("\r","\n"), array('',''), $b);
	$b .= "\n";
	echo $b;
	$temp_time=microtime(true);
}

define('P_DUMP_INTCOLOR','blue');define('P_DUMP_FLOATCOLOR','darkblue');define('P_DUMP_NUMSTRINGCOLOR','#c0c');define('P_DUMP_STRINGCOLOR','darkgreen');define('P_DUMP_RESSCOLOR','#aa0');define('P_DUMP_NULLCOLOR','#aaa');define('P_DUMP_BOOLTRUECOLOR','#0c0');define('P_DUMP_BOOLFALSECOLOR','red');define('P_DUMP_OBJECTCOLOR','auto');define('P_DUMP_PADDINGLEFT','25px');define('P_DUMP_WIDTH','');function pr($a,$b=false){if($b===true){return print_r($a,true);}else{echo print_r($a,true);return '';}}function pDumpTxt($a=null){$d='';if(is_int($a)){$d.='<small><em>entier</em></small> <span style="color:'.P_DUMP_INTCOLOR.';">'.$a.'</span>';}elseif(is_float($a)){$d.='<small><em>décimal</em></small> <span style="color:'.P_DUMP_FLOATCOLOR.';">'.$a.'</span>';}elseif(is_numeric($a)){$d.='<small><em>chaîne numÃ©rique</em> ('.strlen($a).')</small> <span style="color:'.P_DUMP_NUMSTRINGCOLOR.';">\''.$a.'\'</span>';}elseif(is_string($a)){$d.='<small><em>chaîne</em> ('.strlen($a).')</small> <span style="color:'.P_DUMP_STRINGCOLOR.';">\''.htmlspecialchars($a).'\'</span>';}elseif(is_resource($a)){$d.='<small><em>ressource</em></small> <span style="color:'.P_DUMP_RESSCOLOR.';">'.get_resource_type($a).'</span>';}elseif(is_null($a)){$d.='<span style="color: '.P_DUMP_NULLCOLOR.';">null</span>';}elseif(is_bool($a)){$d.='<span style="color: '.($a===true?P_DUMP_BOOLTRUECOLOR:P_DUMP_BOOLFALSECOLOR).';">'.($a===true?'true':'false').'</span>';}elseif(is_object($a)){$d.='<div style="color:'.P_DUMP_OBJECTCOLOR.';"><small>';ob_start();var_dump($a);$e=ob_get_clean();$d.=$e.'</small></div>';}elseif(is_array($a)){$d.='<em>tableau</em> {'.p_dump($a).'}';}else{$d.=$a;}return $d;}function p_dump($f){$d='<div class="p_dump" style="height:auto;min-height:0;margin: 0 auto;'.(P_DUMP_WIDTH?'max-width: '.P_DUMP_WIDTH.';':'').' min-height: 20px;">';$d.='<pre style="height:auto;min-height:0;">';if(!is_array($f)){$d.='<div style="margin-left: 0;">';$d.=pDumpTxt($f);$d.='</div>';}else{$d.='<div style="height:auto;min-height:0;padding-left: '.P_DUMP_PADDINGLEFT.';">';foreach($f as $g=>$a){$d.='<div style="height:auto;min-height:0;">';if(is_int($g)){$d.='<span style="color:'.P_DUMP_INTCOLOR.';">'.$g.'</span>';}else{$d.='<span style="color:'.P_DUMP_STRINGCOLOR.';">\''.$g.'\'</span>';}$d.=' => ';$d.=pDumpTxt($a);$d.='</div>';}$d.='</div>';}$d.='</pre></div>';return $d;}
//function remove_comments(&$a){$b=explode("\n",$a);$a="";$c=count($b);$d=false;for($e=0;$e<$c;$e++){if(preg_match("/^\/\*/",preg_quote($b[$e]))){$d=true;}if(!$d){$a.=$b[$e]."\n";}if(preg_match("/\*\/$/",preg_quote($b[$e]))){$d=false;}}unset($b);return $a;}
//function remove_remarks($f){$b=explode("\n",$f);$f="";$c=count($b);$a="";for($e=0;$e<$c;$e++){if(($e!=($c-1))||(strlen($b[$e])>0)){if(isset($b[$e][0])&&$b[$e][0]!="#"){$a.=$b[$e]."\n";}else{$a.="\n";}$b[$e]="";}}return $a;}
//function split_sql_file($f,$g){$h=explode($g,$f);$f="";$a=array();$k=array();$l=count($h);for($e=0;$e<$l;$e++){if(($e!=($l-1))||(strlen($h[$e]>0))){$m=preg_match_all("/'/",$h[$e],$k);$n=preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/",$h[$e],$k);$o=$m-$n;if(($o%2)==0){$a[]=$h[$e];$h[$e]="";}else{$p=$h[$e].$g;$h[$e]="";$q=false;for($r=$e+1;(!$q&&($r<$l));$r++){$m=preg_match_all("/'/",$h[$r],$k);$n=preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/",$h[$r],$k);$o=$m-$n;if(($o%2)==1){$a[]=$p.$h[$r];$h[$r]="";$p="";$q=true;$e=$r;}else{$p.=$h[$r].$g;$h[$r]="";}}}}}return $a;}
//function get_query_from_file($a){$b=@fread(@fopen($a,'r'),@filesize($a))or die('problem ');$b=remove_remarks($b);$b=split_sql_file($b,';');$b=array_map('trim',$b);return $b;}

class Colors{private $a=array();private $b=array();public function __construct(){$this->a['black']='0;30';$this->a['dark_gray']='1;30';$this->a['blue']='0;34';$this->a['light_blue']='1;34';$this->a['green']='0;32';$this->a['light_green']='1;32';$this->a['cyan']='0;36';$this->a['light_cyan']='1;36';$this->a['red']='0;31';$this->a['light_red']='1;31';$this->a['purple']='0;35';$this->a['light_purple']='1;35';$this->a['brown']='0;33';$this->a['yellow']='1;33';$this->a['light_gray']='0;37';$this->a['white']='1;37';$this->b['black']='40';$this->b['red']='41';$this->b['green']='42';$this->b['yellow']='43';$this->b['blue']='44';$this->b['magenta']='45';$this->b['cyan']='46';$this->b['light_gray']='47';}public function getColoredString($d){return $d;/*$g="";if(isset($this->a[$e])){$g.="\033[".$this->a[$e]."m";}if(isset($this->b[$f])){$g.="\033[".$this->b[$f]."m";}$g.=$d."\033[0m";return $g;*/}public function getForegroundColors(){return array_keys($this->a);}public function getBackgroundColors(){return array_keys($this->b);}}

function ReadStdin($prompt, $valid_inputs, $default = '') {
    while(!isset($input) || (is_array($valid_inputs) && !in_array($input, $valid_inputs)) || ($valid_inputs == 'is_file' && !is_file($input))) {
        echo $prompt;
        $input = strtolower(trim(fgets(STDIN)));
        if(empty($input) && !empty($default)) {
            $input = $default;
        }
    }
    return $input;
}

showtime($temp_time, 'Fin chargement des classes et fonctions');



showtime($temp_time, 'Base de données utilisée : '.$arglocal);
if ($arglocal === 'dist') {
    $new = new Database('localhost', 'corahn_rin', 'uDVi6w!,tUp,1wIVfRq@', 'corahn_rin', '');
    $old = new Database('localhost', 'esteren', 'xXHAPU@mfmvU7cEM3N57', 'esteren', 'est_');
} else {
    $new = new Database('127.0.0.1', 'root', '', 'corahn_rin', '');
    $old = new Database('127.0.0.1', 'root', '', 'esteren', 'est_');
}

showtime($temp_time, 'Initialisation de l\'ancienne et de la nouvelle base de données');

$dt = new DateTime();
$o = new ReflectionObject($dt);
$p = $o->getProperty('date');
$date = $p->getValue($dt);
$datetime = (object) array('date'=>$date);

$nbreq = 0;


/****************************/
/****************************/
/** RESAUVEGARDE DE LA BDD **/
/****************************/
/****************************/
// $newreq = get_query_from_file('new.sql');
// foreach ($newreq as $v) { $new->noRes($v); $nbreq++; }
/****************************/
/****************************/
/****************************/
/****************************/


/*
$line = ReadStdin('Faire un dump de la base de données ? [oui] ', array('','o','oui','n','non'), '1');
$line = ($line === 'oui'
		? 'o'
		: ($line === 'non'
			? 'n'
			: $line));
$line = (int) ($line === 'o');
//$line = stream_get_line(STDIN, 1, "\n");
showtime($temp_time, '');
//$line = 1;
if ((int)$line) {
	showtime($temp_time, 'Exécution de la commande Symfony2 pour refaire le schéma à partir des entités...');
	$r = shell_exec('php ../app/console doctrine:schema:update --force');
	if ($r) {
		$r = str_replace(array("\r","\n"),array('',''),$r);
		$r = trim($r);
		showtime($temp_time, 'Terminé : '.$r.'');
	} else {
		showtime($temp_time, 'Terminé ');
	}
	showtime($temp_time, 'Exécution de la commande mysqldump pour sauvegarder le schéma dans "new.sql"...');
	$r = shell_exec('mysqldump -u root --database corahn_rin --skip-comments -d -q -Q --result-file=new.sql');
	if ($r) {
		$r = str_replace(array("\r","\n"),array('',''),$r);
		$r = trim($r);
		showtime($temp_time, 'Terminé : '.$r.'');
	} else {
		showtime($temp_time, 'Terminé ');
	}
	unset($r);
}
// $new->noRes(file_get_contents('new.sql'));


*/
$del = ReadStdin('Supprimer la bdd ? [o/N]', array('o','n'), 'n');
if (preg_match('#^o#isUu', $del)) {$del = 'o';} else { $del = 'n'; }
if ($del === 'o') {
    showtime($temp_time, 'Suppression de la nouvelle base de données via Symfony2');

    $r = shell_exec('php ../app/console doctrine:database:drop --force');
    if ($r) {
        $r = str_replace(array("\r","\n"),array('',''),$r);
        $r = trim($r);
        showtime($temp_time, 'Terminé : '.$r.'');
    } else {
        showtime($temp_time, 'Terminé ');
    }
    flush();

    showtime($temp_time, 'Création de la nouvelle base de données via Symfony2');
    $r = shell_exec('php ../app/console doctrine:database:create');
    if ($r) {
        $r = str_replace(array("\r","\n"),array('',''),$r);
        $r = trim($r);
        showtime($temp_time, 'Terminé : '.$r.'');
    } else {
        showtime($temp_time, 'Terminé ');
    }

//    $line = ReadStdin('Utiliser Symfony2 pour créer le schéma ? [oui] ', array('','o','oui','n','non'), '1');
//    $line = ($line === 'oui'
//            ? 'o'
//            : ($line === 'non'
//                ? 'n'
//                : $line));
//    $line = (int) ($line === 'o');
//    showtime($temp_time, '');
//    if ((int) $line) {
        showtime($temp_time, 'Exécution de la commande Symfony2 pour refaire le schéma à partir des entités...');
        $r = shell_exec('php ../app/console doctrine:schema:create');
        if ($r) {
            $r = str_replace(array("\r","\n"),array('',''),$r);
            $r = trim($r);
            showtime($temp_time, 'Terminé : '.$r.'');
        } else {
            showtime($temp_time, 'Terminé ');
        }
//    } else {
//        showtime($temp_time, 'Insertion du fichier dump dans la base de données...');
//        $r = shell_exec('mysql -u root --database corahn_rin --execute="source new.sql"');
//        if ($r) {
//            $r = trim($r);
//            showtime($temp_time, 'Terminé : '.$r.'');
//        } else {
//            showtime($temp_time, 'Terminé ');
//        }
//    }
    unset($r);
}
// foreach ($oldreq as $v) { $old->noRes($v); }
// $oldreq = get_query_from_file('old.sql');
// showtime($temp_time, 'Suppression et réinsertion de l\'ancienne base de données');
//unset($oldreq, $newreq);
//*/
$tables = $old->req('SHOW TABLES');
$new_tables = $new->req('SHOW TABLES');
$tables_done = array();
$t=array();
foreach ($new_tables as $v){
	$ta=array_values($v);
	$t[]=$ta[0];
}
$new_tables=$t;
unset($t);
foreach ($tables as $v){
	$t=array_values($v);
	$t=$t[0];
	$t=str_replace('est_','',$t);
	$$t=$old->req('SELECT * FROM %%'.$t);
}unset($t,$q,$v);


showtime($temp_time, 'Récupération de la structure des tables');




$del = ReadStdin('Vider les tables ? [o/N]', array('o','n'), 'n');
if (preg_match('#^o#isUu', $del)) {$del = 'o';} else { $del = 'n'; }
$del = strtolower($del);
if ($del == 'o') {
    $sql = 'SET FOREIGN_KEY_CHECKS=0;'.PHP_EOL;
    foreach ($new_tables as $table) {
        $sql .= 'TRUNCATE `'.$table.'`;'.PHP_EOL;
    }
    $sql .= 'SET FOREIGN_KEY_CHECKS=1;'.PHP_EOL;

    $new->noRes($sql);
    showtime($temp_time, 'Fin du vidage des tables');
}







/*---------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
----------------------------------------- START -----------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------*/
showtime($temp_time, 'Début de l\'import');

//*


$users = $old->prepare('SELECT * FROM `est_users` WHERE `user_id` >= 1 ORDER BY `user_name` ASC ');
$users->execute();
$users = $users->fetchAll(PDO::FETCH_ASSOC);


$del = ReadStdin('Créer les utilisateurs ? [O/n]', array('o','n'), 'n');
if (preg_match('#^o#isUu', $del)) {$del = 'o';} else { $del = 'n'; }

if ($del === 'o') {
    showtime($temp_time, 'Création des utilisateurs via Symfony...');
    $usernb = 0;
    $maxid = 0;
    $table = "users";
    foreach ($users as $v) {
        if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['user_id']))) {
            $pwd = utf8_encode($v['user_email']);
            if ($new->noRes('ALTER TABLE `users` AUTO_INCREMENT = 300')) { showtime($temp_time, 'Réinitialisation de l\'auto-increment pour l\'insertion'); }
            $r = shell_exec('php ../app/console fos:user:create "'.$v['user_name'].'" "'.$v['user_email'].'" '.$pwd.'');
            $usernb++;
            if ($r) {
                $r = str_replace(array("\r","\n"),array('',''),$r);
                $r = trim($r);
                showtime($temp_time, 'Terminé : "'.$v['user_name'].'" "'.$v['user_email'].'" '.$pwd.' / Message : '.$r.'');
            } else {
                showtime($temp_time, 'Terminé "'.$v['user_name'].'" "'.$v['user_email'].'" '.$pwd.'');
            }

            $enter_users = $new->noRes('UPDATE `users` SET `id` = :id WHERE `email` = :email ',array('id'=>$v['user_id'], 'email'=>$v['user_email']));//Exécution de la requête sql
            if ($enter_users) {
                showtime($temp_time, 'Rétablissement de l\'id pour l\'utilisateur "'.$v['user_name'].'"');
            }
        } elseif ($argverbose) {
            echo 'Utilisateur existe déjà : '.$v['user_id'].' > '.$v['user_name'];
        }
        if ($v['user_id'] > $maxid) { $maxid = (int) $v['user_id']; }
    }$tables_done[]='users';
    exec('php ../app/console fos:user:promote pierstoval --super');
    if (!$usernb) { showtime($temp_time, 'Aucun utilisateur à ajouter'); }
    showtime($temp_time, $usernb.' requêtes pour la table "users"');
    if ($new->noRes('ALTER TABLE `users` AUTO_INCREMENT = '.($maxid+1))) {
        showtime($temp_time, 'Réinitialisation de l\'auto-increment après les insertions et rétablissements d\'id pour les utilisateurs');
    }
    $nbreq += $usernb;
}


$fixtures = include 'fixtures.php';

showtime($temp_time, '> Démarrage fixtures');
$tablesTemp = array();
if (is_array($fixtures) && !empty($fixtures)) {
    foreach ($fixtures as $table => $datas) {

        echo "Process ".$table;

        $sql = '
        INSERT INTO `'.$table.'` (';

        $nbreqtemp = 0;
        $nbKey = count($datas[0]);
        $i = 0;
        foreach ($datas[0] as $key => $val) {
            $i++;
            $sql .= ' `'.$key.'` ';
            if ($i < $nbKey) { $sql .= ', '; }
        }
        $sql .= ') VALUES '."\n";
        $nbDatas = count($datas);
        $j = 0;
        foreach ($datas as $k => $data) {

            $process = true;

            if (isset($data['id'])) {
                $sqlTest = 'SELECT * from `'.$table.'` where `id` = :id';
                if ($new->row($sqlTest, array('id'=>$data['id']))) { $process = false; }
            } else {
                if ($table === 'disciplines_domains') {
                    $sqlTest = 'SELECT * from `'.$table.'` where `discipline_id` = :discipline_id and `domain_id` = :domain_id';
                    if ($new->row($sqlTest, array('discipline_id'=>$data['discipline_id'],'domain_id'=>$data['domain_id']))) { $process = false; }

                } elseif ($table === 'disorders_ways') {
                    $sqlTest = 'SELECT * from `'.$table.'` where `disorder_id` = :disorder_id and `way_id` = :way_id';
                    if ($new->row($sqlTest, array('disorder_id'=>$data['disorder_id'],'way_id'=>$data['way_id']))) { $process = false; }

                } elseif ($table === 'jobs_domains') {
                    $sqlTest = 'SELECT * from `'.$table.'` where `jobs_id` = :jobs_id and `domains_id` = :domains_id';
                    if ($new->row($sqlTest, array('jobs_id'=>$data['jobs_id'],'domains_id'=>$data['domains_id']))) { $process = false; }

                } elseif ($table === 'socialclasses_domains') {
                    $sqlTest = 'SELECT * from `'.$table.'` where `socialclasses_id` = :socialclasses_id and `domains_id` = :domains_id';
                    if ($new->row($sqlTest, array('socialclasses_id'=>$data['socialclasses_id'],'domains_id'=>$data['domains_id']))) { $process = false; }

                } else {
                    $process = false;
                }
            }

            if ($process) {

                $sqlPart = $sql . ' ( ';
                $i = 0;
                $j++;
                $params = array();
                foreach ($data as $field => $val) {
                    $i++;
                    $sqlPart .= ' :'.$table.'_'.$field.'_'.$k.' ';
                    $params[$table.'_'.$field.'_'.$k] = $val;
                    if ($i < $nbKey) { $sqlPart .= ', '; }
                    if ($i == $nbKey) { $sqlPart .= ' '; }
                }
                if (array_key_exists('deleted', $params)) {
                    $params['deleted'] = null;
                }
                $sqlPart .= ' ); ';
    //            if ($j < $nbDatas) { $sqlPart .= ', '; }
    //            if ($j == $nbDatas) { $sqlPart .= ';'; }
                $sqlPart .= "\n";
                $nbreq++;
                $nbreqtemp++;
                $tablesTemp[$table] = $table;

                try {
                    $stmt = $new->prepare($sqlPart);
                    $stmt->execute($params);
                } catch (\Exception $e) {
                    echo '--------------------------------------------------------------------',"\r\n";
                    echo "\t",'Erreur d\'insertion',"\r\n";
                    echo "\t",'Exception : ',"\r\n\t\t",$e->getMessage(),"\r\n";
                    echo "\t",'Table : ',"\r\n\t\t",$table,"\r\n";
                    echo "\t",'Requête SQL : ',"\r\n\t\t",str_replace(array("\n","\r","\t"),array('','',''),$sqlPart),"\r\n";
                    echo "\t",'Paramètres : ',"\r\n\t\t", print_r($params,true);
                    echo "\r\n".'--------------------------------------------------------------------';

                    exit;
                }
            }
        }

        echo ' > ' . $nbreqtemp." queries.\r\n";

//        showtime($temp_time, '(via fixtures) '.$nbreqtemp.' requêtes pour la table "'.$table.'"');
    }
} else {
    exit('ERREUR DANS LES FIXTURES !!!');
}

showtime($temp_time, '> Fin fixtures : '.$nbreqtemp.' requêtes exécutées sur les tables suivantes : "'.implode(',', $tablesTemp).'"');


/*
$table = 'weapons';
$nbreqtemp = 0;
foreach ( $armes as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['arme_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'id' => $v['arme_id'],
			'name' => $v['arme_name'],
			'damage' => $v['arme_dmg'],
			'price' => $v['arme_prix'],
			'availability' => $v['arme_dispo'],
			'range' => $v['arme_range'],
			'melee' => (strpos($v['arme_domain'], '2') !== false ? 1 : 0),
			'created' => $datetime->date,
			'updated' => $datetime->date,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');




$table = 'armors';
$nbreqtemp = 0;
foreach ( $armures as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['armure_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'id' => $v['armure_id'],
			'name' => $v['armure_name'],
			'description' => $v['armure_desc'],
			'protection' => $v['armure_prot'],
			'price' => $v['armure_prix'],
			'availability' => $v['armure_dispo'],
			'created' => $datetime->date,
			'updated' => $datetime->date,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');





$table = 'ways';
$nbreqtemp = 0;
$voies_id = array();
$voies_short = array();
foreach ( $voies as $v) {
	$voies_id[$v['voie_id']] = $v;
	$voies_short[$v['voie_shortname']] = $v;
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['voie_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'id' => $v['voie_id'],
			'name' => $v['voie_name'],
			'shortName' => $v['voie_shortname'],
			'description' => $v['voie_desc'],
			'fault' => $v['voie_travers'],
			'created' => $datetime->date,
			'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');




$table = 'disorders';
$nbreqtemp = 0;
foreach ( $desordres as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['desordre_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'id' => $v['desordre_id'],
			'name' => $v['desordre_name'],
			'created' => $datetime->date,
			'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
		$dis_maj = explode(',', $v['desordre_voies_maj']);
		$dis_min = explode(',', $v['desordre_voies_min']);
		$sql = 'INSERT INTO `'.$table.'_ways` SET `disorder_id` = :disorder, `way_id` = :way, `isMajor` = :isMajor';
		$q = $new->prepare($sql);
		$nbreq+=8;
		foreach ($dis_maj as $d) {
			if ($d) {
				$q->execute(array('disorder'=>$v['desordre_id'],'way'=>$d,'isMajor'=>1));
			}
		}
		foreach ($dis_min as $d) {
			if ($d) {
				$q->execute(array('disorder'=>$v['desordre_id'],'way'=>$d,'isMajor'=>0));
			}
		}
	}
}$tables_done[]=$table.'_ways';$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');





$table = 'traits';
$nbreqtemp = 0;
foreach ( $traitscaractere as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['trait_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'id' => $v['trait_id'],
			'way_id' => $voies_short[$v['trait_voie']]['voie_id'],
			'name' => $v['trait_name'],
			'nameFemale' => $v['trait_name_female'],
			'isQuality' => ($v['trait_qd'] === 'q' ? 1 : 0),
			'isMajor' => ($v['trait_mm'] === 'maj' ? 1 : 0),
			'created' => $datetime->date,
			'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');




$table = 'avantages';
$nbreqtemp = 0;
foreach ( $avdesv as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['avdesv_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'id' => $v['avdesv_id'],
			'name' => $v['avdesv_name'],
			'xp' => $v['avdesv_xp'],
			'description' => $v['avdesv_desc'],
			'nameFemale' => $v['avdesv_name_female'],
			'bonusdisc' => $v['avdesv_bonusdisc'],
			'isDesv' => (strpos($v['avdesv_type'], 'desv') !== false ? 1 : 0),
			'isCombatArt' => (strpos($v['avdesv_name'], 'de combat') !== false ? 1 : 0),
			'augmentation' => $v['avdesv_double'],
			'created' => $datetime->date,
			'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');







$table = 'books';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `name` = :name, `description` = :description, `created` = :created, `updated` = :updated';
	$q = $new->prepare($sql);
	$nbreq+=8;
	$nbreqtemp+=8;
	$q->execute(array('id' => 1,'name' => 'Livre 0 - Prologue',				'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 2,'name' => 'Livre 1 - Univers',				'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 3,'name' => 'Livre 2 - Voyages',				'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 4,'name' => 'Livre 2 - Voyages (Réédition)',	'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 5,'name' => 'Livre 3 - Dearg Intégrale',		'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 6,'name' => 'Livre 3 - Dearg Tome 1',			'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 7,'name' => 'Livre 3 - Dearg Tome 2',			'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 8,'name' => 'Livre 3 - Dearg Tome 3',			'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 9,'name' => 'Livre 3 - Dearg Tome 4',			'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 10,'name' => 'Livre 4 - Secrets',				'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 11,'name' => 'Livre 5 - Peuples',				'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 12,'name' => 'Le Monastère de Tuath',			'description' => '','created' => $datetime->date,'updated' => $datetime->date,));
	$q->execute(array('id' => 13,'name' => 'Contenu de la communauté',		'description' => 'Ce contenu est par définition non-officiel.','created' => $datetime->date,'updated' => $datetime->date,));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');











$table = 'jobs';
//$nbreq++;
$nbreqtemp = 0;
foreach ( $jobs as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['job_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['job_id'],
				'book_id' => ($v['job_book'] ? 2 : 13),
				'name' => $v['job_name'],
				'description' => $v['job_desc'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');








$table = 'domains';
$nbreqtemp = 0;
foreach ( $domains as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['domain_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['domain_id'],
				'name' => $v['domain_name'],
				'description' => $v['domain_desc'],
				'way_id' => $v['voie_id'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');









$table = 'jobs_domains';
$nbreqtemp = 0;
if ($new->row('SELECT COUNT(*) FROM %'.$table)) {
    $new->noRes('delete from %'.$table);
    $new->noRes('ALTER TABLE %'.$table.' AUTO_INCREMENT = 1');
}
foreach ( $jobdomains as $v) {
    if (!$v['jobdomain_primsec']) {
        $nbreq++;
        $nbreqtemp++;
        $datas = array(
                'domains_id' => $v['domain_id'],
                'jobs_id' => $v['job_id'],
        );$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
    } else {
        $nbreq++;
        $nbreqtemp++;
        $datas = array(
            'domainPrimary_id' => $v['domain_id'],
            'id' => $v['job_id'],
        );$new->noRes('UPDATE %jobs SET %domainPrimary_id = :domainPrimary_id WHERE %id = :id', $datas);
    }
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');







$table = 'social_class';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
		$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `name` = :name, `description` = :description, `created` = :created, `updated` = :updated';
		$q = $new->prepare($sql);
		$nbreq+=5;
		$nbreqtemp+=5;
		$q->execute(array('id'=>1,'name' => 'Paysan','description' => 'Les roturiers font partie de la majorité de la population. Vous avez vécu dans une famille paysanne, à l\'écart des villes et cités, sans pour autant les ignorer. Vous êtes plus proche de la nature.
		les Demorthèn font également partie de cette classe sociale.', 'created' => $datetime->date,'updated' => $datetime->date,));
		$q->execute(array('id'=>2,'name' => 'Artisan','description' => 'Les roturiers font partie de la majorité de la population. Votre famille était composée d\'un ou plusieurs artisans ou ouvriers, participant à la vie communale et familiale usant de ses talents manuels.', 'created' => $datetime->date,'updated' => $datetime->date,));
		$q->execute(array('id'=>3,'name' => 'Bourgeois','description' => 'Votre famille a su faire des affaires dans les villes, ou tient probablement un commerce célèbre dans votre région, ce qui vous permet de vivre confortablement au sein d\'une communauté familière.', 'created' => $datetime->date,'updated' => $datetime->date,));
		$q->execute(array('id'=>4,'name' => 'Clergé','description' => 'Votre famille a toujours respecté l\'Unique et ses représentants, et vous êtes issu d\'un milieu très pieux.
		Vous avez probablement la foi, vous aussi.', 'created' => $datetime->date,'updated' => $datetime->date,));
		$q->execute(array('id'=>5,'name' => 'Noblesse','description' => 'Vous portez peut-être un grand nom des affaires des grandes cités, ou avez grandi en ville. Néanmoins, votre famille est placée assez haut dans la noblesse pour vous permettre d\'avoir eu des enseignements particuliers.', 'created' => $datetime->date,'updated' => $datetime->date,));
	}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');



$socialclasses = $new->req('SELECT * FROM `social_class`');
$table = 'socialclasses_domains';
$sreq = 0;
$sql = 'INSERT INTO `'.$table.'` SET `socialclasses_id` = :socialclasses_id, `domains_id` = :domain_id';
if (!$new->row('SELECT * FROM %'.$table)) {
	$q = $new->prepare($sql);
	foreach ($socialclasses as $v) {
		if ($v['name'] === 'Paysan') {
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 5));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 8));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 10));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 15));$sreq++;
		} elseif ($v['name'] === 'Artisan') {
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 1));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 16));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 13));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 11));$sreq++;
		} elseif ($v['name'] === 'Bourgeois') {
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 1));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 16));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 12));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 11));$sreq++;
		} elseif ($v['name'] === 'Clergé') {
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 9));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 16));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 11));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 15));$sreq++;
		} elseif ($v['name'] === 'Noblesse') {
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 2));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 16));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 13));$sreq++;
			$q->execute(array('socialclasses_id' => $v['id'], 'domain_id' => 11));$sreq++;
		}
	}
}
$nbreq += $sreq;
$tables_done[]=$table;showtime($temp_time, $sreq.' requêtes pour la table "'.$table.'"');







$table = 'disciplines';
$nbreqtemp = 0;
foreach ( $disciplines as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['disc_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['disc_id'],
				'name' => $v['disc_name'],
				'description' => '',
				'rank' => $v['disc_rang'],
				'book_id' => 2,
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');



$table = 'disciplines_domains';
$nbreqtemp = 0;
foreach ( $discdoms as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %discipline_id = :discipline_id AND %domain_id = :domain_id ', array('discipline_id'=>$v['disc_id'], 'domain_id'=>$v['domain_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
			'discipline_id' => $v['disc_id'],
			'domain_id' => $v['domain_id'],
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');







$table = 'flux';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `flux` SET `id` = :id, `name` = :name, `created` = :created, `updated` = :updated';
	$q = $new->prepare($sql);
	$nbreq+=5;
	$nbreqtemp+=5;
	$q->execute(array('id' => 1,'name' => 'Végétal',	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 2,'name' => 'Minéral',	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 3,'name' => 'Organique',	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 4,'name' => 'Fossile',	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 5,'name' => 'M',			'created' => $datetime->date, 'updated' => $datetime->date,));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');








$table = 'geo_environments';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `name` = :name, `description` = :description, `book_id` = :book_id, `domain_id` = :domain_id, `created` = :created, `updated` = :updated';
	$q = $new->prepare($sql);
	$nbreq+=2;
	$nbreqtemp+=2;
	$q->execute(array('id' => 1,'name' => 'Rural', 'book_id'=>2,'domain_id'=>5, 'description' => 'Votre personnage est issu d\'une campagne ou d\'un lieu relativement isolé.', 'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 2,'name' => 'Urbain', 'book_id'=>2,'domain_id'=>11, 'description' => 'Votre personnage a vécu longtemps dans une ville, suffisamment pour qu\'il ait adopté les codes de la ville dans son mode de vie.', 'created' => $datetime->date, 'updated' => $datetime->date,));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/












$table = 'languages';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `name` = :name, `locale` = :locale';
	$q = $new->prepare($sql);
	$nbreq+=4;
	$nbreqtemp+=4;
	$q->execute(array('id' => 1,'locale' => 'fr','name' => 'Français'));
	$q->execute(array('id' => 2,'locale' => 'en','name' => 'Anglais'));
	$q->execute(array('id' => 3,'locale' => 'de','name' => 'Allemand'));
	$q->execute(array('id' => 4,'locale' => 'es','name' => 'Espagnol'));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');






/*
$table = 'peoples';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `book_id` = :book_id, `name` = :name, `description` = :description, `created` = :created, `updated` = :updated';
	$q = $new->prepare($sql);
	$nbreq+=4;
	$nbreqtemp+=4;
	$q->execute(array('id' => 1,'book_id'=>2,'name' => 'Tri-Kazel', 'description' => 'Les Tri-Kazeliens constituent la très grande majorité de la population de la péninsule. La plupart d\'entre eux conservent une stature assez robuste héritée des Osags mais peuvent aussi avoir des traits d\'autres peuples. Les Tri-Kazeliens sont issus de siècles de mélanges entre toutes les cultures ayant un jour ou l\'autre foulé le sol de la péninsule.<br /><br />De par cette origine, le PJ connaît un dialecte local ; il faut donc préciser de quel pays et région il est originaire.',	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 2,'book_id'=>2,'name' => 'Tarish', 'description' => 'D\'origine inconnue, le peuple Tarish forme une minorité nomade qui parcourt depuis des décennies les terres de la péninsule. Il est aussi appelé "peuple de l\'ouest" car la légende veut qu\'il soit arrivé par l\'Océan Furieux. Les Tarishs se distinguent des Tri-Kazeliens par des pommettes hautes, le nez plutôt aquilin et les yeux souvent clairs. Beaucoup d\'entre eux deviennent des saltimbanques, des mystiques ou des artisans.<br />La culture Tarish, même si elle est diluée aujourd\'hui, conserve encore une base importante : c\'est un peuple nomade habitué aux longs périples et leur langue n\'a pas disparu, bien qu\'aucun étranger ne l\'ait jamais apprise.',	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 3,'book_id'=>2,'name' => 'Osag', 'description' => "Habitués à ne compter que sur eux-mêmes, les Osags forment un peuple rude. Généralement dotés d'une carrure imposante, ils sont les descendants directs des clans traditionnels de la péninsule. La civilisation péninsulaire a beaucoup évolué depuis l'avènement des Trois Royaumes, mais certains clans sont restés fidèles aux traditions ancestrales et n'ont pas pris part à ces changements. Repliés sur leur mode de vie clanique, les Osags ne se sont pas métissés avec les autres peuples et ont gardé de nombreuses caractéristiques de leurs ancêtres. Les Osags font de grands guerriers et comptent parmi eux les plus célèbres Demorthèn.<br /><br />Leur langue a elle aussi survécu au passage des siècles. Les mots \"feondas\", \"C'maogh\", \"Dàmàthair\" - pour ne citer qu'eux - viennent tous de ce que les Tri-Kazeliens nomment la langue ancienne, mais qui est toujours utilisée par les Osags.",	'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 4,'book_id'=>2,'name' => 'Continent', 'description' => "Les hommes et les femmes du Continent sont souvent plus minces et plus élancés que les natifs de Tri-Kazel. Leur visage aura tendance à être plus fin mais avec des traits parfois taillés à la serpe. Un PJ choisissant ce peuple ne sera pas natif du Continent, mais plutôt le descendant direct d'au moins un parent Continental. Si les origines Continentales du PJ sont davantage diluées, on estime qu'il fait partie du peuple de Tri-Kazel.<br /><br />En fonction du passé de la famille du PJ et de son niveau d'intégration dans la société tri-kazelienne, il pourrait avoir appris leur langue d'origine Continentale ou bien un patois de la péninsule, au choix du PJ.",	'created' => $datetime->date, 'updated' => $datetime->date,));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/







$table = 'games';
//$nbreq++;
$nbreqtemp = 0;
foreach ( $games as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['game_id'])) && $v['game_mj']) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['game_id'],
				'name' => $v['game_name'],
				'summary' => $v['game_summary'],
				'gmNotes' => $v['game_notes'],
				'gameMaster_id' => $v['game_mj'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');








/*
$table = 'steps';
$nbreqtemp = 0;
foreach ( $steps as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['gen_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['gen_id'],
				'step' => $v['gen_step'],
				'slug' => preg_replace('#^[0-9]+_#isUu','', $v['gen_mod']),
				'title' => $v['gen_anchor'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/






















$table = 'mails';
//$nbreq++;
$nbreqtemp = 0;
foreach ($mails as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['mail_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['mail_id'],
				'code' => $v['mail_code'],
				'content' => $v['mail_contents'],
				'subject' => $v['mail_subject'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');


$table = 'mails_sent';
//$nbreq++;
$nbreqtemp = 0;
foreach ( $mails_sent as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['mail_sent_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$dest = json_decode($v['mail_dest'], true);
		$datas = array(
				'id' => $v['mail_sent_id'],
				'toName' => (isset($dest['name']) ? $dest['name'] : ''),
				'toEmail' => (isset($dest['mail']) ? $dest['mail'] : ''),
				'mail_id' => $v['mail_id'],
				'content' => $v['mail_content'],
				'subject' => $v['mail_subj'],
				'created' => $v['mail_date'],
				'updated' => $v['mail_date'],
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');












/*
$table = 'regions';
//$nbreq++;
$nbreqtemp = 0;
foreach ( $regions as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['region_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['region_id'],
				'name' => $v['region_name'],
				'description' => $v['region_desc'],
				'kingdom' => $v['region_kingdom'],
				'coordinates' => $v['region_htmlmap'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
            'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');











$table = 'setbacks';
$nbreqtemp = 0;
foreach ( $revers as $v) {
	if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>$v['rev_id']))) {
		$nbreq++;
		$nbreqtemp++;
		$datas = array(
				'id' => $v['rev_id'],
				'name' => $v['rev_name'],
				'description' => $v['rev_desc'],
				'malus' => $v['rev_malus'],
				'created' => $datetime->date,
				'updated' => $datetime->date,
                'deleted'=>null,
		);$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);
	}
}$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/





//*/











/*
$table = 'maps';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `name` = :name, `nameSlug` = :nameSlug, `maxZoom` = :maxZoom, `image` = :image, `description` = :description, `created` = :created, `updated` = :updated, ';
	$q = $new->prepare($sql);
	$nbreq+=1;
	$nbreqtemp+=1;
	$q->execute(array('id' => 1,'name' => 'Tri-Kazel', 'nameSlug'=>'tri-kazel', 'image'=>'uploads/maps/esteren_nouvelle_cartepg_91220092.jpeg','maxZoom'=>10, 'description' => 'Carte de Tri-Kazel officielle, réalisée par Chris',	'created' => $datetime->date, 'updated' => $datetime->date,));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/


/*
$table = 'zones';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {
	$sql = 'INSERT INTO `'.$table.'` SET `id` = :id, `name` = :name,`coordinates` = :coordinates,`map_id` = :map_id, `created` = :created, `updated` = :updated';
	$q = $new->prepare($sql);
	$nbreq+=2;
	$nbreqtemp+=2;
	$q->execute(array('id' => 1,'name' => 'Calvaire', 'coordinates'=>'931,501 892,510 883,525 848,533 851,545 785,579 789,586 749,596 764,614 751,619 754,628 728,639 719,634 707,656 698,661 692,674 672,681 677,691 656,708 640,706 631,730 598,754 598,763 605,771 605,787 610,803 567,816 543,831 519,850 534,866 575,879 574,887 553,899 554,915 570,926 582,922 589,935 604,938 612,948 604,954 605,968 588,977 591,986 648,994 670,985 669,972 693,967 693,958 711,950 702,939 753,916 757,898 807,890 816,917 886,909 857,847 861,843 873,841 885,846 886,841 868,820 870,771 860,753 882,719 883,716 911,705 936,686 961,686 975,668 973,654 1013,644 1014,633 1036,631 992,610 975,595 974,577 1011,567 1010,557 1030,553 1027,540 1030,531 993,524 975,505 956,503 943,496','map_id'=>1, 'created' => $datetime->date, 'updated' => $datetime->date,));
	$q->execute(array('id' => 2,'name' => 'Île aux Cairns', 'coordinates'=>'2584,2999 2511,3039 2524,3070 2517,3087 2487,3093 2503,3110 2468,3151 2496,3161 2516,3156 2525,3162 2509,3174 2484,3180 2462,3172 2445,3199 2423,3206 2432,3217 2449,3216 2497,3259 2449,3284 2466,3310 2447,3317 2433,3336 2428,3397 2444,3406 2429,3421 2465,3427 2445,3444 2476,3441 2472,3471 2455,3458 2462,3488 2480,3503 2489,3489 2520,3500 2538,3509 2543,3524 2543,3544 2576,3541 2662,3538 2684,3527 2803,3515 2814,3497 2822,3467 2912,3460 2871,3425 2866,3388 2827,3354 2846,3346 2847,3333 2896,3320 2894,3308 2847,3286 2862,3278 2847,3250 2836,3242 2837,3225 2861,3213 2834,3181 2856,3179 2837,3150 2783,3119 2783,3119 2801,3092 2794,3078 2814,3075 2786,3045 2764,3035 2756,3016 2730,3020 2698,3001 2708,2992 2713,2974 2701,2956 2689,2959 2673,2987 2708,2992 2708,2992 2698,3001 2667,3006 2649,2996 2625,3012 2599,2978 2601,2953 2589,2951 2576,2971 2531,2961 2519,2970 2503,2965 2452,2999 2492,3005 2520,3011 2563,3000 2558,2983 2531,2961 2576,2971 2585,2983 2599,2978 2625,3012','map_id'=>1, 'created' => $datetime->date, 'updated' => $datetime->date,));
}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/


/*
$table = 'menus';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {

	$nbreq+=29;
	$nbreqtemp+=29;

    $sql = <<<'SQL'
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
INSERT INTO `menus` (`id`, `parent_id`, `name`, `position`, `roles`, `route`, `created`, `updated`, `deleted`) VALUES
(1, NULL, 'Administration', 0, 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:12:"ROLE_MANAGER";}', NULL, '2014-01-31 18:18:28', '2014-01-31 18:18:28', 0),
(2, 10, 'Cartes', 0, 'a:1:{i:0;s:15:"ROLE_ADMIN_MAPS";}', 'esterenmaps_maps_maps_adminlist', '2014-01-30 22:44:34', '2014-01-31 00:06:01', 0),
(3, 11, 'Éditer', 0, 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:12:"ROLE_MANAGER";}', 'fos_user_profile_edit', '2014-01-30 22:46:46', '2014-01-31 00:09:05', 0),
(4, 11, 'Voir', 0, 'a:1:{i:0;s:9:"ROLE_USER";}', 'fos_user_profile_show', '2014-01-30 22:52:28', '2014-01-31 00:09:22', 0),
(8, 10, 'Marqueurs', 0, 'a:1:{i:0;s:15:"ROLE_ADMIN_MAPS";}', 'esterenmaps_maps_markers_adminlist', '2014-01-30 23:14:17', '2014-01-31 00:06:49', 0),
(9, 10, 'Factions', 0, 'a:1:{i:0;s:15:"ROLE_ADMIN_MAPS";}', 'esterenmaps_maps_factions_adminlist', '2014-01-30 23:14:48', '2014-01-31 00:07:41', 0),
(10, 1, 'Esteren Maps', 2, 'a:1:{i:0;s:15:"ROLE_ADMIN_MAPS";}', NULL, '2014-01-30 23:19:28', '2014-02-01 15:48:28', 0),
(11, 1, 'Profil', 1, 'a:1:{i:0;s:9:"ROLE_USER";}', NULL, '2014-01-31 00:08:50', '2014-02-01 15:48:07', 0),
(12, 1, 'Tableau de bord', 0, 'a:1:{i:0;s:9:"ROLE_USER";}', 'pierstoval_admin_admin_index', '2014-01-31 00:09:51', '2014-01-31 18:19:25', 0),
(14, 16, 'Traductions', 0, 'a:1:{i:0;s:21:"ROLE_ADMIN_TRANSLATOR";}', 'pierstoval_translation_translate_adminlist', '2014-01-30 22:41:19', '2014-01-31 21:41:59', 0),
(16, 1, 'Site', 4, 'a:1:{i:0;s:22:"ROLE_ADMIN_MANAGE_SITE";}', NULL, '2014-01-31 19:35:07', '2014-02-01 15:48:56', 0),
(17, 16, 'Menus', 0, 'a:1:{i:0;s:22:"ROLE_ADMIN_MANAGE_SITE";}', 'esteren_pages_menus_adminlist', '2014-01-31 21:44:13', '2014-01-31 21:44:13', 0),
(18, 1, 'Corahn-Rin', 3, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', NULL, '2014-02-01 15:49:16', '2014-02-15 21:17:22', 0),
(19, 18, 'Armures', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_armors_adminlist', '2014-02-01 15:49:50', '2014-02-01 15:49:50', 0),
(20, 18, 'Artefacts', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_artifacts_adminlist', '2014-02-01 16:43:29', '2014-02-01 16:43:29', 0),
(21, 18, 'Avantages / Désavantages', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_avantages_adminlist', '2014-02-01 17:59:00', '2014-02-01 17:59:00', 0),
(22, 18, 'Livres', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_books_adminlist', '2014-02-01 20:08:10', '2014-02-01 20:08:10', 0),
(23, 18, 'Disciplines', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_disciplines_adminlist', '2014-02-02 16:15:14', '2014-02-02 17:01:54', 0),
(24, 18, 'Domaines', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_domains_adminlist', '2014-02-02 17:01:38', '2014-02-02 17:01:38', 0),
(25, 10, 'Routes', 0, 'a:1:{i:0;s:15:"ROLE_ADMIN_MAPS";}', 'esterenmaps_maps_routes_adminlist', '2014-02-12 16:04:05', '2014-02-12 16:04:05', 0),
(26, 18, 'Flux', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_flux_adminlist', '2014-02-15 14:27:29', '2014-02-15 14:27:29', 0),
(27, 18, 'Métiers', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_jobs_adminlist', '2014-02-15 15:06:31', '2014-02-15 15:06:31', 0),
(28, 18, 'Miracles', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_miracles_adminlist', '2014-02-15 20:43:39', '2014-02-15 20:43:39', 0),
(29, 18, 'Ogham', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_ogham_adminlist', '2014-02-15 21:01:51', '2014-02-15 21:01:51', 0),
(30, 18, 'Peuples', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_peoples_adminlist', '2014-02-15 22:41:51', '2014-02-15 22:47:03', 0),
(31, 18, 'Revers', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_setbacks_adminlist', '2014-02-15 22:46:53', '2014-02-15 22:46:53', 0),
(32, 18, 'Classes sociales', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_socialclasses_adminlist', '2014-02-15 22:56:14', '2014-02-15 22:56:30', 0),
(33, 18, 'Traits de caractère', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_traits_adminlist', '2014-02-15 23:21:49', '2014-02-15 23:21:49', 0),
(34, 18, 'Armes', 0, 'a:1:{i:0;s:20:"ROLE_ADMIN_GENERATOR";}', 'corahnrin_admin_weapons_adminlist', '2014-02-16 00:33:15', '2014-02-16 00:33:15', 0);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;


SQL;
    $new->query($sql);

}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/






/*
$table = 'factions';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {

	$nbreq+=8;
	$nbreqtemp+=8;

    $sql = <<<'SQL'

        SET FOREIGN_KEY_CHECKS=0;
        SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
        SET AUTOCOMMIT=0;
        START TRANSACTION;
        SET time_zone = "+00:00";
INSERT INTO `factions` (`id`, `name`, `description`, `created`, `updated`, `deleted`) VALUES
(1, 'Temple', 'Les adeptes de la religion du Temple.', '2014-02-05 15:27:32', '2014-02-05 15:27:32', 0),
        (2, 'Magience', 'Les partisans d''une société régie par des principes académiques & scientifiques.', '2014-02-05 15:29:24', '2014-02-05 15:29:24', 0),
        (3, 'Démorthèn', 'Les populations honorant les cultes et traditions ancestrales de Tri Kazel.', '2014-02-05 15:30:48', '2014-02-05 15:30:48', 0),
        (4, 'Neutre', 'Aucun des grands courants idéologiques ne dominent ce lieu.', '2014-02-05 15:34:04', '2014-02-05 15:34:04', 0),
        (5, 'Osags', 'Rattachés au culte Démorthèn, les Osags en sont peut être l''expression la plus radicale.', '2014-02-05 15:35:35', '2014-02-05 15:35:35', 0),
        (6, 'Tarish', 'Peuple nomade par excellence, ses communautés sont en mouvement constant.', '2014-02-05 15:38:04', '2014-02-05 15:38:04', 0),
        (7, 'Loge botaniste', 'L''école magientiste appliquée aux plantes, herbes et végétaux en général.', '2014-02-05 15:40:50', '2014-02-05 15:40:50', 0),
        (8, 'Loge minéraliste', 'L''école magientiste appliquée aux pierres, métaux et minéraux en général.', '2014-02-05 18:08:38', '2014-02-05 18:08:38', 0);
        SET FOREIGN_KEY_CHECKS=1;
        COMMIT;

SQL;
    $new->query($sql);

}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/




/*
$table = 'markers';
$nbreqtemp = 0;
if (!$new->row('SELECT * FROM %'.$table.' WHERE %id = :id', array('id'=>1))) {

	$nbreq+=4;
	$nbreqtemp+=4;

    $sql = <<<'SQL'
    SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
    SET time_zone = "+00:00";
    INSERT INTO `markers` (`id`, `faction_id`, `map_id`, `name`, `coordinates`, `created`, `updated`, `deleted`, `markerType_id`) VALUES
    (1, NULL, 1, 'Ard Amrach', '1555,728', '2014-02-14 14:40:17', '2014-02-14 14:40:17', 0, 1),
    (2, NULL, 1, 'Rhingal', '1621,889', '2014-02-14 14:40:17', '2014-02-14 14:40:17', 0, 1),
    (3, NULL, 1, 'Calvaire', '791,841', '2014-02-14 15:07:39', '2014-02-14 15:07:39', 0, 1),
    (4, NULL, 1, 'Bois déchiré', '847,593', '2014-02-14 15:07:39', '2014-02-14 15:07:39', 0, 1);

SQL;
    $new->query($sql);

}
$tables_done[]=$table;showtime($temp_time, $nbreqtemp.' requêtes pour la table "'.$table.'"');
*/



























require __DIR__.'/../src/CorahnRin/CharactersBundle/Classes/Money.php';

showtime($temp_time, 'Suppression du contenu des tables d\'association');

use CorahnRin\CharactersBundle\Classes\Money as Money;
$table = 'characters';
$t = $new->req('describe %'.$table);

$new->noRes('delete from %characters_ways');
$new->noRes('delete from %characters_domains');
//$new->noRes('delete from %characters_social_class');
$new->noRes('delete from %characters_avantages');
$new->noRes('delete from %characters_armors');
$new->noRes('delete from %characters_weapons');
$new->noRes('delete from %characters_setbacks');
$new->noRes('delete from %characters_disciplines');
$new->noRes('delete from %characters_flux');

showtime($temp_time, 'Terminé !');

$to_add = array();
$new->noRes('delete from %'.$table);
$struct = array();foreach($t as $v) { $struct[$v['Field']] = $v; } unset($t);
//pr($struct);
$charreq = 0;
$characters = $old->req('SELECT * FROM %est_characters');
require __DIR__.'/../src/CorahnRin/ToolsBundle/Resources/libs/functions/remove_accents.func.php';


$total_files = count($characters);
$times = array();
$current_file = 0;

foreach ( $characters as $v) {
	//if (!$new->row('SELECT * FROM %'.$table.' WHERE %name = :name', array('name'=>$v['char_name']))) {
        $time_char = microtime(true);
        $current_file++;
		echo '----------------------------',"\n";
		echo '----------------------------',"\n";
		$cnt = json_decode($v['char_content']);
		$money = new Money();
		$money->addBraise($cnt->inventaire->argent);
		$money->convert();
		$nameSlug = \CorahnRinTools\remove_accents($v['char_name']);
		$nameSlug = preg_replace('~[^a-zA-Z0-9_-]+~isUu', '-', $nameSlug);
        $nameSlug = preg_replace('~--+~isUu', '-', $nameSlug);

        $nameSlugBase = $nameSlug;
        $i = '';
        $exists = $new->req('SELECT %id FROM %'.$table.' WHERE %nameSlug = :nameSlug', array('nameSlug'=>$nameSlugBase));
        $exists = true;
        while ($exists) {
            echo 'Slug existe déjà : '.$nameSlugBase."\r\n";
            $i++;
            $nameSlugBase = $nameSlug . $i;
            $exists = $new->row('SELECT %id FROM %'.$table.' WHERE %nameSlug = :nameSlug', array('nameSlug'=>$nameSlugBase));
        }
        $nameSlug = $nameSlugBase;

		$domaines = $cnt->domaines;
		$socialclassdomains = array();
		foreach ($domaines as $d => $domain) {
			if ($domain->val > 0 && count($socialclassdomains) < 2) {
//                print_r(array($d=>$domain));print_r(array('domaines'=>$domaines));exit;
                $socialclassdomains[] = $domain->id;
                $domaines->$d->val --;
            }
		}
        $cnt->classe_sociale = $new->row('SELECT %id FROM %social_class WHERE %name = ?', array($cnt->classe_sociale));
        $cnt->classe_sociale = $cnt->classe_sociale['id'];
		$datas = array(
			'id' => $v['char_id'],
			'name' => $v['char_name'],
			'nameSlug' => $nameSlug,
			'job_id' => is_numeric($v['char_job']) ? $v['char_job'] : null,
			'jobCustom' => !is_numeric($v['char_job']) ? $v['char_job'] : null,
			'sex' => substr($cnt->details_personnage->sexe, 0, 1) === 'H' ? 'M' : 'F',
			'age' => $cnt->age,
			'playerName' => $cnt->details_personnage->joueur,
			'region_id' => $v['char_origin'],
			'story' => $cnt->details_personnage->histoire,
			'description' => $cnt->details_personnage->description,
			'facts' => isset($cnt->details_personnage->faits) ? $cnt->details_personnage->faits : '',
			'geoLiving' => $cnt->residence_geographique,//urbain/rural
			'people_id' => (
                $v['char_people'] === 'Tri-Kazel' ? 1
                : ($v['char_people'] === 'Tarish' ? 2
                : ($v['char_people'] === 'Osag' ? 3
                : ($v['char_people'] === 'Continent' ? 4 : 0)))
            ),
			'mentalResist' => $cnt->resistance_mentale->exp,
			'health' => $cnt->sante,
			'stamina' => $cnt->vigueur,
			'defense' => $cnt->defense->amelioration,
			'speed' => $cnt->rapidite->amelioration,
			'survival' => $cnt->survie,
            'hardening' => 0,
			'trauma' => $cnt->traumatismes->curables,
			'traumaPermanent' => $cnt->traumatismes->permanents,
			'rindath' => 0,
			'money' => serialize($money),
			'game_id' => $new->row('select * from games where id = ?', array($v['game_id'])) ? $v['game_id'] : null,
			'user_id' => $new->row('select * from users where id = ?', array($v['user_id'])) ? $v['user_id'] : null,
			'disorder_id' => $cnt->desordre_mental->id,
			'exaltation' => 0,
			'orientation' => $cnt->orientation->name === 'Instinctive' ? 'Instinctive' : 'Rational',
			'traitFlaw_id' => $cnt->traits_caractere->defaut->id,
			'traitQuality_id' => $cnt->traits_caractere->qualite->id,
			'experienceActual' => (int) $cnt->experience->reste,
			'experienceSpent' => $cnt->experience->total - $cnt->experience->reste,
			'status' => $v['char_status'],
			'SocialClassDomain1_id' => $socialclassdomains[0],
			'SocialClassDomain2_id' => $socialclassdomains[1],
			'socialClasses_id' => $cnt->classe_sociale,
			'inventory' => serialize(array_merge($cnt->inventaire->possessions)),
			'created' => date('Y-m-d H:i:s', (int) $v['char_date_creation']),
			'updated' => date('Y-m-d H:i:s', ((int) $v['char_date_update'] ? (int) $v['char_date_update'] : (int) $v['char_date_creation'])),
		);
		$new->noRes('INSERT INTO %'.$table.' SET %%%fields', $datas);

		$charreq++;
		showtime($temp_time, $charreq.' Ajout du personnage '.$v['char_id'].' : '.$v['char_name']);

		$voies = $cnt->voies;
		$countvoies = 0;
		foreach ($voies as $voie) {
			$datasVoies = array( 'character_id' => $v['char_id'], 'way_id' => $voie->id, 'score' => $voie->val, );
			if (!$new->row('SELECT * FROM %characters_ways WHERE %character_id = :character_id AND %way_id = :way_id AND %score = :score', $datasVoies)) {
				$new->noRes('INSERT INTO %characters_ways SET %%%fields', $datasVoies); $countvoies++;
			}
		}
		if ($countvoies === 5) { showtime($temp_time, ' Ajout des voies OK'); }



		$countdoms = 0;
		foreach ($domaines as $domain) {
			$datasDoms = array( 'character_id' => $v['char_id'], 'domain_id' => $domain->id, 'score' => $domain->val, );
			if (!$new->row('SELECT * FROM %characters_domains WHERE %character_id = :character_id AND %domain_id = :domain_id AND %score = :score', $datasDoms)) {
				$new->noRes('INSERT INTO %characters_domains SET %%%fields', $datasDoms); $countdoms++;
			}
			$discs = (array) $domain->disciplines;
			if (!empty($discs)) {
				foreach ($discs as $disc) {
					$assoDiscId = $new->row('SELECT * FROM %disciplines_domains WHERE %discipline_id = :discipline_id AND %domain_id = :domain_id ', array('discipline_id'=>$disc->id,'domain_id'=>$domain->id));
					$id = isset($assoDiscId['discipline_id']) ? $assoDiscId['discipline_id'] : null;
					if (!$id) { exit('Erreur...'.print_r($v, true)); }
					$datasDisc = array( 'character_id' => $v['char_id'], 'domain_id' => $domain->id, 'score' => $disc->val, 'discipline_id' => $id);
					if (!$new->row('SELECT * FROM %characters_disciplines WHERE %character_id = :character_id AND %discipline_id = :discipline_id AND %score = :score AND %domain_id = :domain_id', $datasDisc)) {
						$new->noRes('INSERT INTO %characters_disciplines SET %%%fields', $datasDisc); $countdoms++;
						showtime($temp_time, ' Ajout d\'une discipline');
					}
				}
			}
		}
		showtime($temp_time, ' Ajout des domaines OK');

		$revers = $cnt->revers;
		$revdatas = array('character_id' => $v['char_id']);
		$all_rev = array();
		$avoid = false;
		$avoided = 0;
		foreach ($revers as $rev) {
			$all_rev[$rev->id] = array('character_id' => $v['char_id'], 'setback_id' => $rev->id, 'isAvoided' => 0);
		}
		if (isset($all_rev[10])) {
			$avoid = false;
			foreach ($all_rev as $k => $val) { if ($k !== 10 && $avoid === false) { $all_rev[$k]['isAvoided'] = 1; $avoid = true; } }
		}
		foreach ($all_rev as $val) {
			$new->noRes('INSERT INTO %characters_setbacks SET %%%fields ', $val);
		}
		if (count($all_rev)) {
            showtime($temp_time, ' Ajout de '.count($all_rev).' revers '.($avoid ? ' dont un évité ' : ''));
        }

		$avtg = $cnt->avantages;
		$desv = $cnt->desavantages;
		$combat = $cnt->arts_combat;

		$avtgnb = 0; $desvnb = 0; $combatnb = 0;
		foreach ($avtg as $val) { $new->noRes('INSERT INTO %characters_avantages SET %%%fields', array('character_id'=>$v['char_id'], 'avantage_id' => $val->id, 'doubleValue' => $val->val)); $avtgnb++; }
		foreach ($desv as $val) { $new->noRes('INSERT INTO %characters_avantages SET %%%fields', array('character_id'=>$v['char_id'], 'avantage_id' => $val->id, 'doubleValue' => $val->val)); $desvnb++; }
		foreach ($combat as $val) { $new->noRes('INSERT INTO %characters_avantages SET %character_id = :character_id, %avantage_id = (SELECT %id FROM `avantages` WHERE `name` LIKE :type), %doubleValue = :doubleValue', array('character_id'=>$v['char_id'], 'doubleValue' => 0, 'type' => '%'.$val->name.'%')); $combatnb++; }
		if ($desvnb) { showtime($temp_time, ' Ajout de '.$desvnb.' désavantage(s) '); }
		if ($combatnb) { showtime($temp_time, ' Ajout de '.$combatnb.' art(s) de combat '); }

		$flux = $cnt->flux;
		$sql = 'INSERT INTO %characters_flux SET %character_id = :character_id, %flux = (SELECT %id FROM `flux` WHERE `name` LIKE :type), %quantity = :qty';
		$addflux = 0;
		if ($flux->mineral > 0) { $new->noRes($sql, array('character_id' => $v['char_id'], 'qty' => $flux->mineral, 'type' => 'mineral')); $addflux++; }
		if ($flux->vegetal > 0) { $new->noRes($sql, array('character_id' => $v['char_id'], 'qty' => $flux->vegetal, 'type' => 'vegetal')); $addflux++; }
		if ($flux->fossile > 0) { $new->noRes($sql, array('character_id' => $v['char_id'], 'qty' => $flux->vegetal, 'type' => 'fossile')); $addflux++; }
		if ($flux->organique > 0) { $new->noRes($sql, array('character_id' => $v['char_id'], 'qty' => $flux->organique, 'type' => 'organique')); $addflux++; }
		if ($addflux) { showtime($temp_time, ' Ajout de '.$addflux.' types de flux '); }

        $t = 'artifacts';
		if (!empty($cnt->artefacts)) {
			foreach ($cnt->artefacts as $val) {
                $val = trim($val);
                if ($val) {
                    $val = ucfirst(strtolower($val));
                    if (!$new->row('SELECT * FROM %'.$t.' WHERE %name = :name', array('name'=>$val))) {
                        $new->noRes('INSERT INTO %'.$t.''
                                . 'SET %name = :name',
                        array('name'=>$val, 'created' => $datetime->date, 'updated' => $datetime->date,));
                        //$to_add['artefacts'][$val] = (isset($to_add['artefacts'][$val]) ? $to_add['artefacts'][$val] + 1 : 1);
                    }

                }
			}
		}
        $t = 'ogham';
		if (!empty($cnt->ogham)) {
			foreach ($cnt->ogham as $val) {
                $val = trim($val);
                if ($val) {
                    $to_add['ogham'][$val] = (isset($to_add['ogham'][$val]) ? $to_add['ogham'][$val] + 1 : 1);
                }
			}
		}
        $t = 'miracles';
		if (!empty($cnt->miracles->majeurs)) {
			foreach ($cnt->miracles->majeurs as $val) {
                $val = trim($val);
                if ($val) {
                    $to_add['miracles_maj'][$val] = (isset($to_add['miracles_maj'][$val]) ? $to_add['miracles_maj'][$val] + 1 : 1);
                }
			}
		}
		if (!empty($cnt->miracles->mineurs)) {
			foreach ($cnt->miracles->mineurs as $val) {
                $val = trim($val);
                if ($val) {
                    $to_add['miracles_min'][$val] = (isset($to_add['miracles_min'][$val]) ? $to_add['miracles_min'][$val] + 1 : 1);
                }
			}
		}

//		$sql = 'INSERT INTO %charsocialclass SET %character_id = :character_id, %domain1_id = :dom1, %domain2_id = :dom2, %created = :created, %updated = :updated, %socialClasses_id = (SELECT Id FROM `socialclass` WHERE `name` LIKE :socialClass)';
//		$charsocialclass = array(
//			'character_id' => $v['char_id'],
//			'dom1' => $socialclassdomains[0],
//			'dom2' => $socialclassdomains[1],
//			'socialClass' => $cnt->classe_sociale,
//			'created' => date('Y-m-d H:i:s', (int) $v['char_date_creation']),
//			'updated' => date('Y-m-d H:i:s', (int) $v['char_date_creation']),
//		);
//		if ($new->noRes($sql, $charsocialclass)) { showtime($temp_time, ' Ajout des domaines de la classe sociale du personnage'); }


		foreach ($cnt->inventaire->armes as $arme) { $new->noRes('INSERT INTO %characters_weapons SET %%%fields ', array('characters_id' => $v['char_id'], 'weapons_id' => $arme->id)); }
		if (!empty($cnt->inventaire->armes)) { showtime($temp_time, ' Ajout des armes du personnage'); }

		foreach ($cnt->inventaire->armures as $armure) { $new->noRes('INSERT INTO %characters_armors SET %%%fields ', array('characters_id' => $v['char_id'], 'armors_id' => $armure->id)); }
		if (!empty($cnt->inventaire->armures)) { showtime($temp_time, ' Ajout des armures du personnage'); }

	//	showtime($temp_time, 'Structure manquante pour la table "'.$table.'"');
		foreach ($struct as $k => $s) {
			if (!array_key_exists($s['Field'], $datas) && $s['Field'] !== 'deleted') { echo $s['Field']."\n"; }
		}
	//}
	//usleep(250000);

    $time_char = microtime(true) - $time_char;
    $p = ($current_file * 100 / $total_files);
    $p = number_format($p, 2, '.', '');
    $str = 0;
    $str = "\n".'['.  str_pad(number_format($time_char*1000, 0, '.',' '), 10, ' ', STR_PAD_LEFT).'ms]'."\t".'[';
    $p2 = (int)($p/2);
    for ($i = 0; $i <= 50; $i++) {
        $str .= $p2 < $i ? ' ' : ($p2 === $i ? '>' : '=');
    }
    $str .= ']';
    $times[] = $time_char;
    if (count($times)) {
        $median = array_sum($times) / count($times);
    } else {
        $median = 60*60*24*365;
    }
    $time_remaining = gmdate("H:i:s", $median * ($total_files - $current_file));
    $remaining = '  Remaining: '.$time_remaining.' (estimation)';
    $spent = ' Spent: '.gmdate('H:i:s', microtime(true) - $global_time);
    echo "\n\n".' '.$str." ".$p.'% Char '.$current_file.'/'.$total_files."\t".$remaining.$spent." \n\n";
}$tables_done[]=$table;showtime($temp_time, $charreq.' requêtes pour la table "'.$table.'"');
$nbreq += $charreq;
$tables_done[] = 'characters_disciplines';
$tables_done[] = 'characters_domains';
$tables_done[] = 'characters_armors';
$tables_done[] = 'characters_weapons';
$tables_done[] = 'characters_setbacks';
$tables_done[] = 'characters_avantages';
$tables_done[] = 'characters_flux';
//$tables_done[] = 'characters_social_class';
$tables_done[] = 'characters_ways';






// Rétablissement de l'attribut "deleted" à NULL pour palier aux erreurs liées à l'extension SoftDeleteable
$sql = '
    update `armors` set `deleted` = NULL;
    update `artifacts` set `deleted` = NULL;
    update `avantages` set `deleted` = NULL;
    update `books` set `deleted` = NULL;
    update `characters` set `deleted` = NULL;
    update `characters_modifications` set `deleted` = NULL;
    update `disciplines` set `deleted` = NULL;
    update `disorders` set `deleted` = NULL;
    update `disorders_ways` set `deleted` = NULL;
    update `domains` set `deleted` = NULL;
    update `flux` set `deleted` = NULL;
    update `games` set `deleted` = NULL;
    update `geo_environments` set `deleted` = NULL;
    update `jobs` set `deleted` = NULL;
    update `mails` set `deleted` = NULL;
    update `mails_sent` set `deleted` = NULL;
    update `miracles` set `deleted` = NULL;
    update `ogham` set `deleted` = NULL;
    update `peoples` set `deleted` = NULL;
    update `regions` set `deleted` = NULL;
    update `setbacks` set `deleted` = NULL;
    update `social_class` set `deleted` = NULL;
    update `traits` set `deleted` = NULL;
    update `ways` set `deleted` = NULL;
    update `weapons` set `deleted` = NULL;
    update `steps` set `deleted` = NULL;
    update `menus` set `deleted` = NULL;
    update `pages` set `deleted` = NULL;
    update `factions` set `deleted` = NULL;
    update `maps` set `deleted` = NULL;
    update `markers` set `deleted` = NULL;
    update `markers_types` set `deleted` = NULL;
    update `routes` set `deleted` = NULL;
    update `routes_types` set `deleted` = NULL;
    update `zones` set `deleted` = NULL;
';
$new->noRes($sql);





//exit;








/****************************************************
*****************************************************
*******************FIN DE L'IMPORT*******************
*****************************************************
*****************************************************/
// showtime($temp_time, 'Fin de l\'import, <strong style="color: #88f;">'.$nbreq.'</strong> insertions effectuées');
showtime($temp_time, 'Fin de l\'import, '.$colors->getColoredString($nbreq, "green").' insertions effectuées');

showtime($temp_time, 'Reste à ajouter :');
print_r($to_add);

$max = (microtime(true) - $time);
$max = max($total_times);
foreach ($total_msgs as $k => $msg) {
	$t = $total_times[$k];
	$ratio = $t > 0 ? (($t / $max)*750) : 0;
	$ratio *= 1750;
	$color = '#ff0';
	if ($ratio < 0.1) { $ratio = 0; }
	if ($ratio < 25) { $color = '#0a0'; }
	if ($ratio > 50) { $color = '#f70'; }
	if ($ratio > 80) { $color = '#f00'; }
	if ($ratio > 152) { $color = '#f00'; $ratio = 152; }
	// $bar = '<span style="margin-right: 10px; display: inline-block;height: 12px;width:152px;border:solid 1px #777"
		// ><span style="display:inline-block; max-width: 100%; height: 10px;width: '.$ratio.'px;background:'.$color.';"
	// ></span></span>';
	$bar = '';
	for ($i = 0; $i <= $ratio; $i += 10) { $bar .= '|'; }
	echo $bar;
	echo $msg;
}




/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! TABLES À REFAIRE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!! Toutes les tables d'asso où un champ manque !!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/
$tables_err = array();
//$tables_done[] = 'char_disciplines'; $tables_err[] = 'char_disciplines';
//$tables_done[] = 'char_domains'; $tables_err[] = 'char_domains';
//$tables_done[] = 'char_ways'; $tables_err[] = 'char_ways';
//$tables_done[] = 'char_flux'; $tables_err[] = 'char_flux';
//$tables_done[] = 'char_avtgs'; $tables_err[] = 'char_avtgs';
//$tables_done[] = 'char_revers'; $tables_err[] = 'char_revers';
//$tables_done[] = 'disorder_ways'; $tables_err[] = 'disorder_ways';
//$tables_done[] = 'game_players'; $tables_err[] = 'game_players';
/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/






/*---------------------------------------------------------------------------------------
 -----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
---------------------------- TABLES À NE PAS FAIRE CAR MAPS -----------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------*/
$tables_done[] = 'events';
$tables_done[] = 'eventszones';
$tables_done[] = 'eventsroutes';
$tables_done[] = 'eventsroutestypes';
$tables_done[] = 'eventsmarkers';
$tables_done[] = 'eventsmarkerstypes';
$tables_done[] = 'eventsresources';
$tables_done[] = 'factions';
$tables_done[] = 'factions_events';
$tables_done[] = 'foes';
$tables_done[] = 'foes_events';
$tables_done[] = 'maps';
$tables_done[] = 'markers';
$tables_done[] = 'markerstypes';
$tables_done[] = 'npcs';
$tables_done[] = 'npcs_events';
$tables_done[] = 'resources';
$tables_done[] = 'resources_routes';
$tables_done[] = 'resources_routestypes';
$tables_done[] = 'routes';
$tables_done[] = 'routes_markers';
$tables_done[] = 'routestypes';
$tables_done[] = 'routestypes_events';
$tables_done[] = 'weather';
$tables_done[] = 'weather_events';
$tables_done[] = 'zones';


/*---------------------------------------------------------------------------------------
 -----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
---------------------------- TABLES À NE PAS FAIRE CAR VIDES ----------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------*/
$tables_done[] = 'artifacts';
$tables_done[] = 'ogham';
$tables_done[] = 'miracles';
$tables_done[] = 'pages';	//Le CMS ne contient rien au départ, et sera créé plus tard
$tables_done[] = 'users';	//IMPORTANT => Sera fait directement avec FOSUserBundle en ligne de commmande
$tables_done[] = 'groups';	//IMPORTANT => Sera fait directement avec FOSUserBundle en ligne de commmande


echo 'Tables à terminer :',"\n";
foreach ($new_tables as $t) {
	if (!in_array($t, $tables_done)) {
		echo "\n", $colors->getColoredString($t, "green");
	}
}

echo "\n",'Tables à refaire :',"\n";
foreach ($tables_err as $t) {
	echo "\n", $colors->getColoredString($t, "red");
}

showtime($temp_time, 'Finalisation');

echo "\n".'Temps d\'exécution : '.(microtime(true) - $time).' secondes';
echo "\n";