<?php
// -------------------------------------------------------------------------

use Xmf\Request;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_update.tpl';

include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', 'Pedigree database - Update details');

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}
// ( $xoopsUser->isAdmin($xoopsModule->mid() ) )

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$myts = \MyTextSanitizer::getInstance();

$fld = Request::getWord('fld', '', 'GET');
$id  = Request::getInt('id', 0, 'GET');
/*
$fld = $_GET['fld'];
$id  = $_GET['id'];
*/
//query (find values for this owner/breeder (and format them))
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $id;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //ID
    $id = $row['id'];
    //name
    $naaml    = htmlentities(stripslashes($row['lastname']), ENT_QUOTES);
    $naamf    = htmlentities(stripslashes($row['firstname']), ENT_QUOTES);
    $naam     = $naaml . ', ' . $naamf;
    $namelink = '<a href="dog.php?id=' . $row['id'] . '">' . stripslashes($row['naam']) . '</a>';
    //street
    $street = stripslashes($row['streetname']);
    //housenumber
    $housenumber = $row['housenumber'];
    //postcode
    $postcode = $row['postcode'];
    //city
    $city = stripslashes($row['city']);
    //phonenumber
    $phonenumber = stripslashes($row['phonenumber']);
    //email
    $email = stripslashes($row['emailadres']);
    //website
    $web = stripslashes($row['website']);

    //user who entered the info
    $dbuser = $row['user'];
}

//create form
include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new \XoopsThemeForm($naam, 'updatedata', 'updatepage.php', 'post', true);
//hidden value current record owner
$form->addElement(new \XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new \XoopsFormHidden('ownerid', $id));
$form->addElement(new \XoopsFormHidden('curname', $naam));
$form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
//name last
if ('nl' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_OWN_LNAME . '</b>', 'naaml', $size = 50, $maxsize = 255, $value = $naaml));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'lastname'));
    $form->addElement(new \XoopsFormHidden('curvalnamel', $naaml));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_OWN_LNAME, $naaml));
}
//name first
if ('nf' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_OWN_FNAME . '</b>', 'naamf', $size = 50, $maxsize = 255, $value = $naamf));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'firstname'));
    $form->addElement(new \XoopsFormHidden('curvalnamef', $naamf));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_OWN_FNAME, $naamf));
}
//street
if ('st' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_STR . '</b>', 'street', $size = 50, $maxsize = 255, $value = $street));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'streetname'));
    $form->addElement(new \XoopsFormHidden('curvalstreet', $street));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_STR, $street));
}
//housenumber
if ('hn' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_HN . '</b>', 'housenumber', $size = 50, $maxsize = 255, $value = $housenumber));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'housenumber'));
    $form->addElement(new \XoopsFormHidden('curvalhousenumber', $housenumber));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_HN, $housenumber));
}
//postcode
if ('pc' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_PC . '</b>', 'postcode', $size = 50, $maxsize = 255, $value = $postcode));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'postcode'));
    $form->addElement(new \XoopsFormHidden('curvalpostcode', $postcode));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_PC, $postcode));
}
//city
if ('ct' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_CITY . '</b>', 'city', $size = 50, $maxsize = 255, $value = $city));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'city'));
    $form->addElement(new \XoopsFormHidden('curvalcity', $city));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_CITY, $city));
}
//phonenumber
if ('pn' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_PN . '</b>', 'phonenumber', $size = 50, $maxsize = 255, $value = $phonenumber));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'phonenumber'));
    $form->addElement(new \XoopsFormHidden('curvalphonenumber', $phonenumber));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_PN, $phonenumber));
}
//email
if ('em' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_EMAIL . '</b>', 'email', $size = 50, $maxsize = 255, $value = $email));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'emailadres'));
    $form->addElement(new \XoopsFormHidden('curvalemail', $email));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_EMAIL, $email));
}
//website
if ('we' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_WEB . '</b>', 'web', $size = 50, $maxsize = 255, $value = $web));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_owner'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'website'));
    $form->addElement(new \XoopsFormHidden('curvalweb', $web));
} else {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_WEB, $web));
}

//submit button
if ($fld) {
    $form->addElement(new \XoopsFormButton('', 'button_id', _MA_PEDIGREE_BUT_SUB, 'submit'));
}
//add data (form) to smarty template
$xoopsTpl->assign('form', $form->render());

//footer
include XOOPS_ROOT_PATH . '/footer.php';
