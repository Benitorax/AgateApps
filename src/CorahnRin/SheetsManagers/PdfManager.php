<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\SheetsManagers;

use CorahnRin\Entity\Characters;
use CorahnRin\PDF\PDF;
use Pierstoval\Bundle\CharacterManagerBundle\Model\CharacterInterface;
use Pierstoval\Bundle\CharacterManagerBundle\SheetsManagers\SheetGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class PdfManager implements SheetGeneratorInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $sheetsFolder;

    public function __construct(string $sheetsFolder, TranslatorInterface $translator)
    {
        $this->translator   = $translator;
        $this->sheetsFolder = $sheetsFolder;
    }

    private function getFolder()
    {
        return $this->sheetsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function generateSheet(CharacterInterface $character, bool $printer_friendly = false): ?string
    {
        if (!($character instanceof Characters)) {
            throw new \InvalidArgumentException(sprintf(
                'To generate sheet, character must be an instance of "%s". "%s" given.',
                Characters::class, get_class($character)
            ));
        }

        return $this->originalSheet($character, $printer_friendly);
    }

    /**
     * @param Characters $character
     * @param bool       $printer_friendly
     *
     * @return PDF
     * @throws \CorahnRin\Exception\CharactersException
     */
    private function originalSheet(Characters $character, $printer_friendly = false)
    {
        $general_width  = 893;
        $general_height = 1263;
        $pdf            = new PDF('P', 'pt');
        $pdf->SetCompression(false);
        $translator = $this->translator;
        $pdf->setTranslator($translator);

        $p = [
            'lettrine' => [
                'file' => 'LettrinEsteren-Regular.ttf',
                'name' => 'lettrinesteren-regular',
            ],
            'unz'      => [
                'file' => 'UnZialish.ttf',
                'name' => 'unzialish',
            ],
            'caro'     => [
                'file' => 'carolingia.ttf',
                'name' => 'carolingia',
            ],
            'carbold'  => [
                'file' => 'carolingia_old.ttf',
                'name' => 'carolingia_old',
            ],
            'times'    => [
                'file' => 'times.ttf',
                'name' => 'times',
            ],
            'arial'    => [
                'file' => 'arial.ttf',
                'name' => 'arial',
            ],
        ];
        foreach ($p as $key => $v) {
            $file            = __DIR__.'/../Resources/assets/fonts/'.$v['file'];
            $p[$key]['file'] = $file;
            $pdf->AddFont($v['name'], '', $file, true);
        }

        /*--------------------------------*/
        /*---------PREMIÈRE FICHE---------*/
        /*--------------------------------*/

        //*-----------------------------------
        $pdf->AddPage('', [$general_width, $general_height]);
        $pdf->Image($this->getFolder().'/'.
            'original'.
            '_'.$translator->getLocale().
            '_1'.
            '_'.($printer_friendly === true ? 'pf' : 'npf').
            '.jpg', 0, 0, $general_width, $general_height);

        $pdf->textbox($character->getName(), 213, 280, $p['lettrine'], 25, 370);

        $pdf->textbox($character->getPlayerName(), 880, 280, $p['lettrine'], 21, 230);

        $pdf->textline($translator->trans($character->getSex())[0], 215, 322, $p['times'], 18);
        $pdf->textline(substr($character->getAge(), 0, 3), 343, 322, $p['caro'], 18);

        $pdf->multiple_lines($character->getDescription(), 295, 365, $p['carbold'], 17, 820, 1, 0, true);

        $pdf->textline(substr($character->getPeople()->getName(), 0, 20), 530, 322, $p['lettrine'], 18, true);
        $pdf->textline(substr($character->getJobCustom() ?: $character->getJob()->getName(), 0, 25), 895, 322, $p['lettrine'], 18, true);

        // voies
        $pdf->textline($character->getWay('com')->getScore(), 325, 545, $p['carbold'], 28);
        $pdf->textline($character->getWay('emp')->getScore(), 325, 608, $p['carbold'], 28);
        $pdf->textline($character->getWay('cre')->getScore(), 325, 667, $p['carbold'], 28);
        $pdf->textline($character->getWay('rai')->getScore(), 325, 727, $p['carbold'], 28);
        $pdf->textline($character->getWay('ide')->getScore(), 325, 784, $p['carbold'], 28);

        // voies des domaines ligne 1
        $pdf->textline($character->getWay('cre')->getScore(), 290, 990, $p['unz'], 22);
        $pdf->textline($character->getWay('rai')->getScore(), 537, 990, $p['unz'], 22);
        $pdf->textline($character->getWay('rai')->getScore(), 800, 990, $p['unz'], 22);
        $pdf->textline($character->getWay('cre')->getScore(), 1069, 990, $p['unz'], 22);
        // voies des domaines ligne 2
        $pdf->textline($character->getWay('com')->getScore(), 298, 1169, $p['unz'], 22);
        $pdf->textline($character->getWay('emp')->getScore(), 542, 1169, $p['unz'], 22);
        $pdf->textline($character->getWay('ide')->getScore(), 802, 1169, $p['unz'], 22);
        $pdf->textline($character->getWay('rai')->getScore(), 1060, 1169, $p['unz'], 22);
        // voies des domaines ligne 3
        $pdf->textline($character->getWay('emp')->getScore(), 280, 1335, $p['unz'], 22);
        $pdf->textline($character->getWay('emp')->getScore(), 540, 1335, $p['unz'], 22);
        $pdf->textline($character->getWay('com')->getScore(), 820, 1335, $p['unz'], 22);
        $pdf->textline($character->getWay('com')->getScore(), 1085, 1335, $p['unz'], 22);
        // voies des domaines ligne 4
        $pdf->textline($character->getWay('rai')->getScore(), 271, 1502, $p['unz'], 22);
        $pdf->textline($character->getWay('rai')->getScore(), 539, 1502, $p['unz'], 22);
        $pdf->textline($character->getWay('emp')->getScore(), 800, 1502, $p['unz'], 22);
        $pdf->textline($character->getWay('emp')->getScore(), 1065, 1502, $p['unz'], 22);

        // Advantages and disadvantages
        $av = [];
        foreach ($character->getAdvantages() as $v) {
            if (!$v->getAdvantage()->isDesv()) {
                $av[] = $translator->trans($v->getAdvantage()->getName()).($v->getScore() > 1 ? '    x'.$v->getScore() : '');
            }
        }
        if (isset($av[0])) {
            $pdf->textline(substr($av[0], 0, 25), 430, 500, $p['caro'], 18);
        }
        if (isset($av[1])) {
            $pdf->textline(substr($av[1], 0, 25), 430, 540, $p['caro'], 18);
        }
        if (isset($av[2])) {
            $pdf->textline(substr($av[2], 0, 25), 430, 580, $p['caro'], 18);
        }
        if (isset($av[3])) {
            $pdf->textline(substr($av[3], 0, 25), 430, 620, $p['caro'], 18);
        }
        $dv = [];
        foreach ($character->getAdvantages() as $v) {
            if ($v->getAdvantage()->isDesv()) {
                $dv[] = $translator->trans($v->getAdvantage()->getName()).($v->getScore() > 1 ? '    x'.$v->getScore() : '');
            }
        }
        if (isset($dv[0])) {
            $pdf->textline(substr($dv[0], 0, 25), 430, 685, $p['caro'], 18);
        }
        if (isset($dv[1])) {
            $pdf->textline(substr($dv[1], 0, 25), 430, 725, $p['caro'], 18);
        }
        if (isset($dv[2])) {
            $pdf->textline(substr($dv[2], 0, 25), 430, 765, $p['caro'], 18);
        }
        if (isset($dv[3])) {
            $pdf->textline(substr($dv[3], 0, 25), 430, 805, $p['caro'], 18);
        }

        // Santé
//		$health_array = $this->get_health_array();
//		$health = array();
//		foreach ($health_array as $k => $v) {
//			$health[$k] = '';
//			for ($i = 1; $i <= $v; $i++) {
//				$health[$k] .= 'O ';
//			}
//		}
//		$pdf->textline($health['Bon'], 920, 527, $p['times'], 24);
//		$pdf->textline($health['Moyen'], 920, 571, $p['times'], 24);
//		$pdf->textline($health['Grave'], 920, 615, $p['times'], 24);
//		$pdf->textline($health['Critique'], 920, 658, $p['times'], 24);
//		$pdf->textline($health['Agonie'], 920, 700, $p['times'], 24);

        $pdf->textline($character->getStamina(), 1090, 755, $p['caro'], 22);
        $pdf->textline($character->getSurvival(), 1090, 798, $p['caro'], 22);

        // Domaines
        $x_arr = [0, 91, 91, 91, 350, 350, 350, 350, 614, 614, 614, 614, 874, 874, 874, 874, 91];
        $y_arr = [0, 988, 1165, 1331, 988, 1165, 1333, 1499, 988, 1165, 1331, 1499, 988, 1165, 1331, 1499, 1499];
        $j     = 0;
        if ($printer_friendly === true) {
            $pdf->SetTextColor(0x14, 0x14, 0x14);
        } else {
            $pdf->SetTextColor(0x22, 0x11, 0x4);
        }
        foreach ($character->getDomains() as $key => $val) {
            $score = $val->getScore();
            ++$j;
            if ($score >= 0) {
                for ($i = 1; $i <= $score; ++$i) {
                    $pdf->textline('●', $x_arr[$j] + ($i - 1) * 23.75 - 7, $y_arr[$j] + 4, $p['arial'], 29);
                }
            }
//			if ($val['bonus']) {
//				$pdf->textline('+'.$val['bonus'], $x_arr[$j]+52, $y_arr[$j]+23, $p['unz'], 16);
//			}
//			if ($val['malus']) {
//				$pdf->textline('-'.$val['malus'], $x_arr[$j]+143, $y_arr[$j]+23, $p['unz'], 16);
//			}
            $l = 0;
            foreach ($character->getDisciplines() as $charDisciplines) {
                if ($charDisciplines->getDomain()->getId() === $val->getDomain()->getId()) {
                    $pdf->textline($charDisciplines->getDiscipline()->getName(), $x_arr[$j] + 45, $y_arr[$j] + 45 + $l * 22, $p['times'], 13, true);
                    $pdf->textline($charDisciplines->getScore(), $x_arr[$j] + 222, $y_arr[$j] + 45 + $l * 22, $p['caro'], 17);
                    ++$l;
                }
            }
        }
        $pdf->SetTextColor(0, 0, 0);

        //-----------------------------------*/

        /*---------------------------------*/
        /*----------DEUXIÈME FICHE---------*/
        /*---------------------------------*/

        //*-----------------------------------
        $pdf->AddPage('', [$general_width, $general_height]);
        $pdf->Image($this->getFolder().'/'.
            'original'.
            '_'.$translator->getLocale().
            '_2'.
            '_'.($printer_friendly === true ? 'pf' : 'npf').
            '.jpg', 0, 0, $general_width, $general_height);

        $i = 0;
        foreach ($character->getWeapons() as $v) {
            if ($i > 4) {
                break;
            }
            $pdf->textline($v->getName(), 123, 151 + $i * 43, $p['times'], 14, true); // Affichage de l'arme
            $pdf->textline($v->getDamage(), 370, 157 + $i * 43 - 2, $p['caro'], 20);
            ++$i;
        }

        $pdf->textline($character->getPotential(), 335, 366, $p['caro'], 32);

        //Attitudes de combat
        $tir       = $character->getAttackScore('ranged');
        $cac       = $character->getAttackScore('melee');
        $pot       = $character->getPotential();
        $rap       = $character->getBaseSpeed() + $character->getSpeed();
        $def       = $character->getBaseDefense() + $character->getDefense();
        $attitudes = [
            ['tir' => $tir, 'cac' => $cac, 'def' => $def, 'rap' => $rap], //Attitudes standards
            ['tir' => $tir + $pot, 'cac' => $cac + $pot, 'def' => $def - $pot, 'rap' => $rap], //Attitudes offensives
            ['tir' => $tir - $pot, 'cac' => $cac - $pot, 'def' => $def + $pot, 'rap' => $rap], //Attitudes défensives
            ['tir' => $tir, 'cac' => $cac, 'def' => $def - $pot, 'rap' => $rap + $pot], //Attitudes rapide
            ['tir' => 0, 'cac' => 0, 'def' => $def + $pot, 'rap' => $rap], //Attitudes de mouvement
        ];
        $pdf->textline('CàC/Tir', 475, 115, $p['times'], 13, true);
        foreach ($attitudes as $k => $v) {
            $pdf->textline($v['cac'].'/'.$v['tir'], 489, 161 + $k * 54, $p['carbold'], 15);
            $pdf->textline($v['def'], 572, 161 + $k * 54, $p['carbold'], 20);
            $pdf->textline($v['rap'], 650, 161 + $k * 54, $p['carbold'], 20);
        }

        //Défense améliorée
        if ($printer_friendly === true) {
            $pdf->SetTextColor(0x14, 0x14, 0x14);
        } else {
            $pdf->SetTextColor(0x22, 0x11, 0x4);
        }
        if ($charDef = $character->getDefense()) {
            for ($i = 1; $i <= $charDef; ++$i) {
                $off = $i > 5 ? 12 : 0;
                $pdf->textline('●', 767 + ($i - 1) * 27.6 + $off, 136, $p['arial'], 30);
            }
        }

        //Rapidité améliorée
        $charSpeed = $character->getSpeed();
        for ($i = 1; $i <= $charSpeed; ++$i) {
            $pdf->textline('●', 767 + ($i - 1) * 27.6, 219, $p['arial'], 30);
        }

        $pdf->SetTextColor(0, 0, 0);

        if ($character->getArmors()) {
            $arr = $character->getArmors();
            $i   = 0;
            foreach ($arr as $k => $v) {
                if ($i > 3) {
                    break;
                }
//				$v = str_replace("\r", '', $v);
//				$v = str_replace("\n", '', $v);
                $str = $translator->trans($v->getName()).' ('.$v->getProtection().')';
                $pdf->textline($str, 750, 277 + ($i * 31), $p['times'], 14);
                ++$i;
            }
        }

        if ($character->getAdvantages()) {
            $i = 0;
            foreach ($character->getAdvantages() as $v) {
                if ($v->getAdvantage()->isCombatArt()) {
                    $pdf->textline($v->getAdvantage()->getName(), 448, 1026 + ($i * 44), $p['carbold'], 20, true);
                    ++$i;
                }
            }
        }

        $arr = [];
        foreach ($character->getArtifacts() as $k => $v) {
            $arr[] = $translator->trans($v->getName());
        }
        $str = implode(', ', $arr);
        $pdf->multiple_lines($str, 92, 1028, $p['times'], 14, 300, 3, 43);

//		$arr = array();
//		foreach ($character->getPreciousItems() as $v) {
//			if ($v) {
//				$arr[] = $v;
//			}
//		}
//		$str = implode(', ', $arr);
//		$pdf->multiple_lines($str, 800, 790, $p['times'], 14, 300, 3, 43);
//

        //Possessions et équipements
        if ($character->getInventory()) {
            $arr1 = array_slice($character->getInventory(), 0, 10);
            $arr2 = array_slice($character->getInventory(), 10, 10);
            foreach ($arr1 as $i => $v) {
                $pdf->textbox($v, 85, 535 + $i * 42.8, $p['times'], 14, 280, true);
            }
            foreach ($arr2 as $i => $v) {
                $pdf->textbox($v, 445, 535 + $i * 42.8, $p['times'], 14, 280, true);
            }
        }

        if ($character->getOgham()) {
            $arr = $character->getOgham();
            $i   = 0;
            foreach ($arr as $v) {
                if ($i > 5) {
                    break;
                }
                $pdf->textline($v, 147, 1377 + ($i * 43), $p['carbold'], 20, true);
                ++$i;
            }
        }

        if ($character->getMiracles()) {
            $min = [];
            $maj = [];
            foreach ($character->getMiracles() as $miracle) {
                if ($miracle->isMajor()) {
                    $maj[] = $translator->trans($miracle->getName());
                } else {
                    $min[] = $translator->trans($miracle->getName());
                }
            }

            $min = implode(', ', $min);
            $maj = implode(', ', $maj);
            $pdf->multiple_lines($min, 457, 1341, $p['carbold'], 18, 270, 3, 43);
            $pdf->multiple_lines($maj, 457, 1512, $p['carbold'], 18, 270, 3, 43);
        }

        $pdf->textline($character->getRindath().' / '.$character->getRindath(), 195, 1272, $p['carbold'], 32);
        $pdf->textline($character->getExaltation().' / '.$character->getExaltation(), 540, 1272, $p['carbold'], 32);

        //Argent
        $money = $character->getMoney();
        $pdf->textline($money->getEmber(), 830, 540, $p['carbold'], 28);
        $pdf->textline($money->getAzure(), 830, 609, $p['carbold'], 28);
        $pdf->textline($money->getFrost(), 830, 676, $p['carbold'], 28);
        //-----------------------------------*/

        /*---------------------------------*/
        /*---------TROISIÈME FICHE---------*/
        /*---------------------------------*/
        $pdf->AddPage('', [$general_width, $general_height]);
        $pdf->Image($this->getFolder().'/'.
            'original'.
            '_'.$translator->getLocale().
            '_3'.
            '_'.($printer_friendly === true ? 'pf' : 'npf').
            '.jpg', 0, 0, $general_width, $general_height);

        $resist_mentale = $character->getBaseMentalResist() + $character->getMentalResist();
        $pdf->textline($resist_mentale, 323, 528, $p['carbold'], 25);
        $pdf->textline($character->getWay('com')->getScore(), 1050, 897, $p['carbold'], 28);
        $pdf->textline($character->getWay('cre')->getScore(), 1050, 969, $p['carbold'], 28);
        $pdf->textline($character->getWay('emp')->getScore(), 1050, 1041, $p['carbold'], 28);
        $pdf->textline($character->getWay('rai')->getScore(), 1050, 1110, $p['carbold'], 28);
        $pdf->textline($character->getWay('ide')->getScore(), 1050, 1180, $p['carbold'], 28);

        $pdf->multiple_lines($character->getStory(), 90, 173, $p['times'], 14, 1015, 6, 43);

        $str = $translator->trans($character->getRegion()->getName()).' - '.$translator->trans('Milieu').' '.$translator->trans($character->getGeoLiving());
        $pdf->textline($str, 557, 86, $p['caro'], 14);

        $pdf->textline($character->getSocialClass()->getName(), 557, 114, $p['caro'], 14, true);

        if ($character->getSetbacks()) {
            $rev = [];
            foreach ($character->getSetbacks() as $charSetbacks) {
                $rev[] = $translator->trans($charSetbacks->getSetback()->getName());
            }
            $rev = implode(' - ', $rev);
            $pdf->textline($rev, 557, 142, $p['caro'], 14);
        }

        //Points de traumatisme
        $trauma = $character->getTraumaPermanent() + $character->getTrauma();
        $off    = 0;
        for ($i = 1; $i <= $trauma; ++$i) {
            if ($i <= $character->getTraumaPermanent()) {
                $pdf->SetTextColor(0x22, 0x11, 0x4);
            } else {
                $pdf->SetTextColor(0x88, 0x6F, 0x4B);
            }
            if (($i - 1) % 5 === 0) {
                $off += 12;
            }
            $pdf->textline('●', 219 + ($i - 1) * 27.75 + $off, 595, $p['arial'], 32);
        }

        $pdf->SetTextColor(0, 0, 0);

        //Points d'endurcissement
        if ($character->getHardening()) {
            $endurcissement = (int) $character->getHardening();
            $off            = 0;
            if ($printer_friendly === true) {
                $pdf->SetTextColor(0x14, 0x14, 0x14);
            } else {
                $pdf->SetTextColor(0x22, 0x11, 0x4);
            }
            for ($i = 1; $i <= $endurcissement; ++$i) {
                if (($i - 1) % 5 === 0) {
                    $off += 12;
                }
                $pdf->textline('●', 219 + ($i - 1) * 27.75 + $off, 631, $p['arial'], 32);
            }
        }

        //Orientation
        $pdf->textline($character->getConsciousness(), 271, 878, $p['carbold'], 21);
        $pdf->textline($character->getInstinct(), 420, 878, $p['carbold'], 21);
        $pdf->textline($character->getOrientation(), 645, 877, $p['carbold'], 18, true);

        //Désordre mental
        $pdf->textline($character->getMentalDisorder()->getName(), 195, 674, $p['carbold'], 21, true);

        //Qualité et défaut
        $pdf->textline($translator->trans('Qualité').' : '.$translator->trans($character->getQuality()->getName()), 270, 940, $p['carbold'], 21);
        $pdf->textline($translator->trans('Défaut').' : '.$translator->trans($character->getFlaw()->getName()), 270, 982, $p['carbold'], 21);

        //Expérience
        $pdf->textline($character->getExperienceActual().'     ( '.$translator->trans('Total').' '.$character->getExperienceSpent().' )', 679, 1325, $p['carbold'], 24);

        if ($character->getFacts()) {
            $str             = preg_replace('~\n|\r~iU', '', $translator->trans($character->getFacts()));
            $taille_du_texte = 14;
            $police_du_texte = $p['times'];
            $desc            = [0 => ''];
            $arr             = explode(' ', $str, 200);
            $line            = 0;
            foreach ($arr as $word) {
                $teststring = $desc[$line].' '.$word;
                $testbox    = imagettfbbox($taille_du_texte, 0, $police_du_texte['file'], $teststring);
                if ($line === 0) {
                    $larg = 729;
                } elseif ($line === 1) {
                    $larg = 929;
                } elseif ($line === 2) {
                    $larg = 908;
                } elseif ($line === 3) {
                    $larg = 898;
                } elseif ($line === 4) {
                    $larg = 878;
                } elseif ($line > 4) {
                    $larg = 856;
                } else {
                    throw new \InvalidArgumentException('Error when creating image box for PDF.');
                }
                if ($testbox[2] > $larg) {
                    if (!$desc[$line]) {
                        $desc[$line] .= $word;
                    } else {
                        ++$line;
                        $desc[$line] = $word;
                    }
                } else {
                    $desc[$line] .= (!$desc[$line] ? '' : ' ').$word;
                }
            }

            foreach ($desc as $i => $v) {
                $offset = 0;
                if ($i === 0) {
                    $offset = 197;
                } elseif ($i === 2) {
                    $offset = 18;
                } elseif ($i === 3) {
                    $offset = 32;
                } elseif ($i === 4) {
                    $offset = 52;
                } elseif ($i === 5) {
                    $offset = 74;
                }
                if ($i < 5) {
                    $pdf->textline($v, 176 + $offset, 1388 + $i * 43, $police_du_texte, $taille_du_texte);
                } elseif ($i === 5) {
                    $pdf->textline($v.'(...)', 176 + $offset, 1388 + $i * 43, $police_du_texte, $taille_du_texte);
                } else {
                    break;
                }
            }
        }

        return $pdf;
    }
}
