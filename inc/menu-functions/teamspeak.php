<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Teamspeak
 * @param int $js
 * @return bool|mixed|null|string|string[]
 * @throws \phpFastCache\Exceptions\phpFastCacheInvalidArgumentException
 */
function teamspeak($js = 0)
{
    global $cache;

    header('Content-Type: text/html; charset=utf-8');
    if (!fsockopen_support()) return _fopen;

    if (empty($js)) {
        $teamspeak = '
          <div id="navTeamspeakServer">
            <div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">
              <!--
                DZCP.initTeamspeakServer();
              //-->
            </script>
          </div>';

    } else {
        $ts_ip = settings('ts_ip');
        $ts_sport = settings('ts_sport');
        $ts_port = settings('ts_port');
        if (!empty($ts_ip) && !empty($ts_sport) && !empty($ts_port)) {
            $CachedString = $cache->getItem('teamspeak_' . $_SESSION['language']);
            if (is_null($CachedString->get()) || isset($_GET['cID'])) {
                $teamspeak = teamspeak3();
                $CachedString->set($teamspeak)->expiresAfter(config('cache_teamspeak'));
                $cache->save($CachedString);
            } else {
                $teamspeak = $CachedString->get();
            }
        } else {
            $teamspeak = '<br /><div style="text-align:center;">' . _no_ts . '</div><br />';
        }
    }

    return $teamspeak;
}