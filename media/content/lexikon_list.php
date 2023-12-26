<?php
/*
 Lexikon Archiv
 Copyright (c) 2007 Southbridge - Lutz & Stroh GbR.
 
 www.southbridge.de
 */


$module_smarty = new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');

/*
 ITEMS PER PAGE
 */

if(PRODUCT_LIST_ITEMS_PER_PAGE != ''){
    $ipp_end = explode(",", PRODUCT_LIST_ITEMS_PER_PAGE);
    $pp_end = end($ipp_end);
    $pp_start = $ipp_end[0];
    
    if(!isset($_GET['p']) || !is_numeric($_GET['p'])){
        $_GET['p'] = $pp_start;
    }elseif($_GET['p']> $pp_end){
        $_GET['p'] = $pp_end;
    }
}

$_GET['p'] = 1000;
$ipage = FILENAME_CONTENT;

if($_GET['keywords'])
    $ipage = FILENAME_ADVANCED_SEARCH_RESULT;
    
    
    /*     if(PRODUCT_LIST_ITEMS_PER_PAGE != ''){
     $ipp = explode(",", PRODUCT_LIST_ITEMS_PER_PAGE);
     foreach($ipp as $value){
     $items = $value;
     if($_GET['p'] == $value) $items = '<strong>' .$value. '</strong>';
     
     
     
     
     
     $items_per_page .= 'test<a href="'.xtc_href_link($ipage,'p='.$value .'&'. xtc_get_all_get_params(array('p'))).'">'.$items.'</a> ';
     
     
     
     }
     } */
    
    /*
     CONTENT TEXT
     */
    
    $shop_content_query = xtc_db_query("SELECT
                        content_id,
        
        
        
        
        
        
                                         content_title,
                                         content_group,
                        content_heading,
                        content_text
                        FROM ".TABLE_CONTENT_MANAGER."
                        WHERE content_group='".(int)$_GET['coID']."'
                                                                                     ".$group_check."
                        AND languages_id='".(int) $_SESSION['languages_id']."'");
    
    
    $content_data = xtc_db_fetch_array($shop_content_query);
    
    $module_smarty->assign('CONTENT_HEADING', $content_data['content_heading']);
    $module_smarty->assign('CONTENT_TEXT', $content_data['content_text']);
    
    /*
     ABC AUFBAUEN
     */
    
    $abc_array = array();
    
    // suma
    $SEF_parameter = '';
    // BOF lolly - Fehler bei Aufrufen aller Buchstaben
    // $link = xtc_href_link(FILENAME_CONTENT, 'coID='.(int)$_GET['coID']);
    $link = xtc_href_link(FILENAME_CONTENT, 'coID='.(int)$_GET['coID']);
    // EOF lolly - Fehler bei Aufrufen aller Buchstaben
    /*
     if (SEARCH_ENGINE_FRIENDLY_URLS == 'true')
     {
     $SEF_parameter = 'information/'.$content_data['content_group'].'/'.xtc_cleanName($content_data['content_title']).'.html';
     $link = strtolower($SEF_parameter);
     $link = xtc_href_link($link);
     }
     */
    // suma EOF
    
    // Alle als Start
    $abc_array[] = array(
        'link'          => $link,
        'buchstabe' => TEXT_LEXIKON_ALL,
        'active'        => $active
    );
    
    foreach(range('A', 'Z') AS $alphabet){
        $active = '';
        if($_GET['buchstabe'] == $alphabet) $active = 1;
        
        // suma
        $SEF_parameter = '';
        // BOF lolly - Fehler bei Aufrufen einzelner Buchstaben
        //$link = xtc_href_link(FILENAME_CONTENT, 'cmcID='.(int)$_GET['cmcID'].'&buchstabe='.$alphabet);
        $link = xtc_href_link(FILENAME_CONTENT, 'coID='.(int)$_GET['coID'].'&buchstabe='.$alphabet);
        // EOF lolly - Fehler bei Aufrufen einzelner Buchstaben
        
        if (SEARCH_ENGINE_FRIENDLY_URLS == 'true')
        {
            $SEF_parameter = FILENAME_CONTENT.'?coID='.(int)$_GET['coID'].'&buchstabe='.$alphabet;
            //$link = strtolower($SEF_parameter);
            $link = xtc_href_link($SEF_parameter);
        }
        
        // suma EOF
        
        $abc_array[] = array(
            'link'          => $link,
            'buchstabe' => $alphabet,
            'active'        => $active
        );
    }
    
    $module_smarty->assign('abc', $abc_array);
    $t = range('A', 'Z');
    
    // BOF lolly - SQL Problem bei Aufruf der lexicon_list.php
    // $like = " WHERE ";
    $link = xtc_href_link(FILENAME_CONTENT, 'coID='.(int)$_GET['coID'].'&buchstabe='.$alphabet);
    // EOF lolly - SQL Problem bei Aufruf der lexicon_list.php
    
    $key = strtoupper($_GET['buchstabe']);
    
    if(isset($_GET['buchstabe']) && in_array($key,$t)){
        // BOF lolly - Fehler bei Aufrufen einzelner Buchstaben
        // $like = "WHERE keyword LIKE '".$_GET['buchstabe']."%' AND ";
        $like = "WHERE keyword LIKE '".$_GET['buchstabe']."%'";
        // EOF lolly - Fehler bei Aufrufen einzelner Buchstaben
    }
    
    $lexikon_query = "SELECT
                            id,
                            keyword,
                            description
                    FROM
                            " . TABLE_LEXIKON . "
                            ".$like."
ORDER BY keyword";  // BOF - sort keywords in frontend lexikon list - EOF
    $lexikon_split = new splitPageResults($lexikon_query, (int)$_GET['page'], (int)$_GET['p']);
    
    if (($lexikon_split->number_of_rows> 0))
    {
        $navigation = '
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <tr>
                                            <td class="smallText">'.$lexikon_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS).'</td>
                                            <td align="right" class="smallText">'.TEXT_RESULT_PAGE . ' ' . $lexikon_split->display_links(MAX_DISPLAY_PAGE_LINKS, xtc_get_all_get_params(array('page', 'info', 'x', 'y'))).'</td>
                                    </tr>
                            </table>';
    }
    
    //      $module_smarty->assign('NAVIGATION', $navigation);
    //      $module_smarty->assign('PAGEITEMS', TEXT_ITEMS_PER_PAGE . $items_per_page);
    
    $all_array = array();
    
    if($lexikon_split->number_of_rows> 0)
    {
        $lexikon_query = xtc_db_query($lexikon_split->sql_query);
        while ($lexikon_data = xtc_db_fetch_array($lexikon_query,true))
        {
            $all_array[] = array(
                'name'                  => $lexikon_data['keyword'],
                'description'   => $lexikon_data['description']
            );
        }
    }else{
        $all_array[] = array('name' => TEXT_LEXIKON_NO_RECORDS, 'description' => TEXT_LEXIKON_NO_RECORDS_DESC);
    }
    
    $module_smarty->assign('language', $_SESSION['language']);
    $module_smarty->assign('lexikon', $all_array);
    
    if(!CacheCheck()) {
        $module_smarty->caching = 0;
        echo $module_smarty->fetch(CURRENT_TEMPLATE.'/module/lexikon.html');
    }else{
        $cache=true;
        $module_smarty->caching = 1;
        $module_smarty->cache_lifetime = CACHE_LIFETIME;
        $module_smarty->cache_modified_check = CACHE_CHECK;
        $cache_id = $_GET['buchstabe'].$_SESSION['language'].$_SESSION['customers_status']['customers_status_id'];
        
        echo $module_smarty->fetch(CURRENT_TEMPLATE.'/module/lexikon.html', $cache_id);
    }
    //}
    ?>
