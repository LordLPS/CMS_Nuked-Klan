<?php
/**
 * admin.php
 *
 * Backend of Guestbook module
 *
 * @version     1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2016 Nuked-Klan (Registred Trademark)
 */
defined('INDEX_CHECK') or die('You can\'t run this file alone.');

if (! adminInit('Guestbook'))
    return;


function edit_book($gid)
{
    global $nuked, $language;

    $sql = mysql_query("SELECT name, comment, email, url FROM " . GUESTBOOK_TABLE . " WHERE id = '" . $gid . "'");
    list($name, $comment, $email, $url) = mysql_fetch_array($sql);

    $url = nkHtmlEntities($url);

    echo "<div class=\"content-box\">\n" //<!-- Start Content Box -->
    . "<div class=\"content-box-header\"><h3>" . _EDITTHISPOST . "</h3>\n"
    . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Guestbook.php\" rel=\"modal\">\n"
    . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
    . "</div></div>\n"
    . "<div class=\"tab-content\" id=\"tab2\"><form method=\"post\" action=\"index.php?file=Guestbook&amp;page=admin&amp;op=modif_book\" onsubmit=\"backslash('guest_text');\">\n"
    . "<table style=\"margin-left: auto;margin-right: auto;text-align: left;\" cellspacing=\"0\" cellpadding=\"2\"border=\"0\">\n"
    . "<tr><td><b>" . __('AUTHOR') . " :</b></td><td>" . $name . "</td></tr>\n"
    . "<tr><td><b>" . _MAIL . " : </b></td><td><input type=\"text\" name=\"email\" size=\"40\" value=\"" . $email . "\" /></td></tr>\n"
    . "<tr><td><b>" . _URL . " : </b></td><td><input type=\"text\" name=\"url\" size=\"40\" value=\"" . $url . "\" /></td></tr>\n";

    echo "<tr><td colspan=\"2\"><b>" . _COMMENT . " :</b></td></tr>\n"
    . "<tr><td colspan=\"2\"><textarea class=\"editor\" id=\"guest_text\" name=\"comment\" cols=\"65\" rows=\"12\">" . $comment . "</textarea></td></tr>\n"
    . "<tr><td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"gid\" value=\"" . $gid . "\" /></td></tr></table>\n"
    . "<div style=\"text-align: center;\"><br /><input class=\"button\" type=\"submit\" name=\"send\" value=\"" . _MODIF . "\" /><a class=\"buttonLink\" href=\"index.php?file=Guestbook&amp;page=admin\">" . __('BACK') . "</a></div></form><br /></div>\n";
}

function modif_book($gid, $comment, $email, $url)
{
    global $nuked, $user;

    $comment = nkHtmlEntityDecode($comment);
    $comment = mysql_real_escape_string(stripslashes($comment));

    if (!empty($url) && !is_int(stripos($url, 'http://')))
    {
        $url = "http://" . $url;
    }

    $sql = mysql_query("UPDATE " . GUESTBOOK_TABLE . " SET email = '" . $email . "', url = '" . $url . "', comment = '" . $comment . "' WHERE id = '" . $gid . "'");

    saveUserAction(_ACTIONMODIFBOOK .'.');

    printNotification(_POSTEDIT, 'success');
    setPreview('index.php?file=Guestbook', 'index.php?file=Guestbook&page=admin');
}

function del_book($gid)
{
    global $nuked, $user;

    $sql = mysql_query("DELETE FROM " . GUESTBOOK_TABLE . " WHERE id = '" . $gid . "'");

    saveUserAction(_ACTIONDELBOOK .'.');

    printNotification(_POSTDELETE, 'success');
    setPreview('index.php?file=Guestbook', 'index.php?file=Guestbook&page=admin');
}

function main()
{
    global $nuked, $language;

    $nb_mess_guest = "30";

    $sql2 = mysql_query("SELECT id FROM " . GUESTBOOK_TABLE);
    $count = mysql_num_rows($sql2);

    if(array_key_exists('p', $_REQUEST)){
        $page = $_REQUEST['p'];
    }
    else{
        $page = 1;
    }
    $start = $page * $nb_mess_guest - $nb_mess_guest;

    echo "<script type=\"text/javascript\">\n"
    . "<!--\n"
    . "\n"
    . "function delmess(pseudo, id)\n"
    . "{\n"
    . "if (confirm('" . _SIGNDELETE . " '+pseudo+' ! " . _CONFIRM . "'))\n"
    . "{document.location.href = 'index.php?file=Guestbook&page=admin&op=del_book&gid='+id;}\n"
    . "}\n"
    . "\n"
    . "// -->\n"
    . "</script>\n";

    echo "<div class=\"content-box\">\n" //<!-- Start Content Box -->
    . "<div class=\"content-box-header\"><h3>" . _ADMINGUESTBOOK . "</h3>\n"
    . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Guestbook.php\" rel=\"modal\">\n"
    . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
    . "</div></div>\n"
    . "<div class=\"tab-content\" id=\"tab2\">\n";

    nkAdminMenu(1);

    if ($count > $nb_mess_guest)
    {
        number($count, $nb_mess_guest, "index.php?file=Guestbook&amp;page=admin");
    }


    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n"
    . "<tr>\n"
    . "<td style=\"width: 20%;\" align=\"center\"><b>" . _DATE . "</b></td>\n"
    . "<td style=\"width: 25%;\" align=\"center\"><b>" . __('AUTHOR') . "</b></td>\n"
    . "<td style=\"width: 25%;\" align=\"center\"><b>" . _IP . "</b></td>\n"
    . "<td style=\"width: 15%;\" align=\"center\"><b>" . _EDIT . "</b></td>\n"
    . "<td style=\"width: 15%;\" align=\"center\"><b>" . _DEL . "</b></td></tr>\n";

    $sql = mysql_query("SELECT id, date, name, host FROM " . GUESTBOOK_TABLE . " ORDER BY id DESC LIMIT " . $start . ", " . $nb_mess_guest."");
    while (list($id, $date, $name, $ip) = mysql_fetch_array($sql))
    {
        $date = nkDate($date);
        $name = nk_CSS($name);

        echo "<tr>\n"
        . "<td style=\"width: 20%;\" align=\"center\">" . $date . "</td>\n"
        . "<td style=\"width: 25%;\" align=\"center\">" . $name . "</td>\n"
        . "<td style=\"width: 25%;\" align=\"center\">" . $ip . "</td>\n"
        . "<td style=\"width: 15%;\" align=\"center\"><a href=\"index.php?file=Guestbook&amp;page=admin&amp;op=edit_book&amp;gid=" . $id . "\"><img style=\"border: 0;\" src=\"images/edit.gif\" alt=\"\" title=\"" . _EDITTHISPOST . "\" /></a></td>\n"
        . "<td style=\"width: 15%;\" align=\"center\"><a href=\"javascript:delmess('" . addslashes($name) . "', '" . $id . "');\"><img style=\"border: 0;\" src=\"images/del.gif\" alt=\"\" title=\"" . _DELTHISPOST . "\" /></a></td></tr>\n";
    }

    if ($count == "0")
    {
        echo "<tr><td align=\"center\" colspan=\"5\">" . _NOSIGN . "</td></tr>\n";
    }

    echo "</table>";

    if ($count > $nb_mess_guest)
    {
        number($count, $nb_mess_guest, "index.php?file=Guestbook&amp;page=admin");
    }

    echo "<div style=\"text-align: center;\"><br /><a class=\"buttonLink\" href=\"index.php?file=Admin\">" . __('BACK') . "</a></div><br /></div></div>\n";
}

function main_pref()
{
    global $nuked, $language;

    echo "<div class=\"content-box\">\n" //<!-- Start Content Box -->
    . "<div class=\"content-box-header\"><h3>" . _PREFS . "</h3>\n"
    . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Guestbook.php\" rel=\"modal\">\n"
    . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
    . "</div></div>\n"
    . "<div class=\"tab-content\" id=\"tab2\">\n";

    nkAdminMenu(2);

    echo "<form method=\"post\" action=\"index.php?file=Guestbook&amp;page=admin&amp;op=change_pref\">\n"
    . "<table style=\"margin-left: auto;margin-right: auto;text-align: left;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n"
    . "<tr><td>" . _GUESTBOOKPG . " :</td><td><input type=\"text\" name=\"mess_guest_page\" size=\"2\" value=\"" . $nuked['mess_guest_page'] . "\" /></td></tr>\n"
    . "</table><div style=\"text-align: center;\"><br /><input class=\"button\" type=\"submit\" name=\"Submit\" value=\"" . __('SEND') . "\" /><a class=\"buttonLink\" href=\"index.php?file=Guestbook&amp;page=admin\">" . __('BACK') . "</a></div>\n"
    . "</form><br /></div></div>\n";
}

function change_pref($mess_guest_page)
{
    global $nuked, $user;

    $upd = mysql_query("UPDATE " . CONFIG_TABLE . " SET value = '" . $mess_guest_page . "' WHERE name = 'mess_guest_page'");

    saveUserAction(_ACTIONPREFBOOK .'.');

    printNotification(_PREFUPDATED, 'success');
    redirect("index.php?file=Guestbook&page=admin", 2);
}

function nkAdminMenu($tab = 1)
{
    global $language, $user, $nuked;

    $class = ' class="nkClassActive" ';
?>
    <div class= "nkAdminMenu">
        <ul class="shortcut-buttons-set" id="1">
            <li <?php echo ($tab == 1 ? $class : ''); ?>>
                <a class="shortcut-button" href="index.php?file=Guestbook&amp;page=admin">
                    <img src="modules/Admin/images/icons/speedometer.png" alt="icon" />
                    <span><?php echo _GUESTBOOK; ?></span>
                </a>
            </li>
            <li <?php echo ($tab == 2 ? $class : ''); ?>>
                <a class="shortcut-button" href="index.php?file=Guestbook&amp;page=admin&amp;op=main_pref">
                    <img src="modules/Admin/images/icons/process.png" alt="icon" />
                    <span><?php echo _PREFS; ?></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
<?php
}


switch ($GLOBALS['op']) {
    case "edit_book":
        edit_book($_REQUEST['gid']);
        break;

    case "modif_book":
        modif_book($_REQUEST['gid'], $_REQUEST['comment'], $_REQUEST['email'], $_REQUEST['url']);
        break;

    case "del_book":
        del_book($_REQUEST['gid']);
        break;

    case "main_pref":
        main_pref();
        break;

    case "change_pref":
        change_pref($_REQUEST['mess_guest_page']);
        break;

    default:
        main();
        break;
}

?>