<?php
    function print_r_tree($data)
    {
        // capture the output of print_r
        $out = print_r($data, true);

        // replace something like '[element] => <newline> (' with <a href="javascript:toggleDisplay('...');">...</a><div id="..." style="display: none;">
        $out = preg_replace('/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iUe',"'\\1<a href=\"javascript:toggleDisplay(\''.(\$id = substr(md5(rand().'\\0'), 0, 7)).'\');\">\\2</a><div id=\"'.\$id.'\" style=\"display: none;\">'", $out);

        // replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
        $out = preg_replace('/^\s*\)\s*$/m', '</div>', $out);

        // print the javascript function toggleDisplay() and then the transformed output
        echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>'."\n$out";
    }
    function print_r2($val){
        echo '<pre>';
        print_r($val);
        echo  '</pre>';
    }
    function read_print_csv($filename)
    {
        $row = 1;
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num champs à la ligne $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }
    function create_table($filename)
    {
        $num = 0;
        $base_fact_code =array();
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($num == 0){
                    $header = $data;
                }
                else {
                    $i=0;
                    $row=array();
                    foreach ($header as $key) {
                        $row[$key] = $data[$i];
                        $i++;
                    }
                $table[]=$row;
                }
                $num++;
            }
            fclose($handle);
            return($table);
        }
    }
    function split_codes($str_codes)
    {
        $pattern="/(\d+)|(\+\d+)|(\-\d+)/";
        //echo "BASEFACT STRING LINE : $line <br>\n";
        while (preg_match($pattern,$str_codes,$matches) > 0)
        {
            //print_r2($matches);echo "<br>\n";
            $length=strlen($matches[0]);
            $str_codes= substr($str_codes,$length);
            //echo "REMAINING line string : $line <br>\n";
            $codes_list[]=$matches[0];
        }
        return($codes_list);
    }
    
    function analyze_table($table)
    {
        foreach ($table as $row) {
                $code_list= split_codes($row['BaseFact']);
                echo "LISTE DES CODES = " ;print_r2($code_list);
                
        }
    }
//Main   
    $table = create_table("../data/TVAcodes-achats.csv");
    print_r_tree($table);
    analyze_table($table);
    ?>
    