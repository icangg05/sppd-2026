<?php
require('fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data, $border=array(), $align=array(), $style=array(), $maxline=array())
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++){
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	}
	if (count($maxline)) {
			$_maxline = max($maxline);
			if ($nb > $_maxline) {
				$nb = $_maxline;
			}
		}
    $h=3*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
		// alignment
		$a = isset($align[$i]) ? $align[$i] : 'L';
		// maxline
		$m = isset($maxline[$i]) ? $maxline[$i] : false;
		
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        //$this->Rect($x,$y,$w,$h);
		//Draw the border
			if ($border[$i]==1) {
				$this->Rect($x,$y,$w,$h);
			} else {
				$_border = strtoupper($border[$i]);
				if (strstr($_border, 'L')!==false) {
					$this->Line($x, $y, $x, $y+$h);
				}
				if (strstr($_border, 'R')!==false) {
					$this->Line($x+$w, $y, $x+$w, $y+$h);
				}
				if (strstr($_border, 'T')!==false) {
					$this->Line($x, $y, $x+$w, $y);
				}
				if (strstr($_border, 'B')!==false) {
					$this->Line($x, $y+$h, $x+$w, $y+$h);
				}
			}
		// Setting Style
		if (isset($style[$i])) {
			$this->SetFont('', $style[$i]);
		}
        //Print the text
        $this->MultiCell($w,3,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

protected $col = 0; // Current column
protected $y0;      // Ordinate of column start

function SetCol($col)
{
    // Set position at a given column
    $this->col = $col;
    $x = 110+$col*65;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    // Method accepting or not automatic page break
    if($this->col<1)
    {
        // Go to next column
        $this->SetCol($this->col+1);
        // Set ordinate to top
        $this->SetY(0);
        // Keep on page
        return false;
    }
    else
    {
        // Go back to first column
        $this->SetCol(0);
        // Page break
        return true;
    }
}

}
?>