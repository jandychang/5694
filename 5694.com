server
{
    listen       80;
    server_name  new.5694.com;
    index index.html index.htm index.php;
    root  /data/web/5694_2.com;

        if (!-e $request_filename) {  
           rewrite  ^/(.*)$  /index.php/$1  last;  
           break;  
        }

    location ~ .*\.php$ {
        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        expires off;
    }
    access_log  /data/logs/new.5694.log  access;
}
server
{
    listen       80;
    server_name  m.5694.com;
    index index.html index.htm index.php;
    root  /data/web/5694.com;
    location ~ .*\.php$ {
        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        expires off;
    }
    rewrite ^/?$ "/h/m/index.htm" last;
    rewrite ^/index "/" permanent;
    rewrite ^/([hd](\d+)()?(_\d+)?)$ "/h/m/$1.htm" last;
    rewrite ^/([a-z_]+(_hot)?(_\d+)?)$ "/h/m/$1.htm" last;
    rewrite ^/([a-z_]+_(tv|dianying)(_\d+)?)$   "/h/m/$1.htm"   last;
    rewrite ^/(\d+)$ "/h/m/$1.htm" last;
    rewrite ^/(\d+)p$ "/h/mp/$1.htm" last;
    rewrite ^/tpl.*$ "http://www.5694.com/" permanent;
    rewrite ^/([a-z0-9_]+)$   "/h/m/$1.htm"   last;
    error_page  404   /m404.html;
    access_log  /data/logs/m.5694.log  access;
}

server
{
    listen       80;
    server_name  5694.com www.5694.com yyse.org www.yyse.org;
    index index.html index.htm index.php;
    root  /data/web/5694.com;

    location ~ .*\.php$
    {
        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        expires off;
    }
    rewrite ^/killing1.html$             "http://www.5694.com/979" permanent;
    rewrite ^/simon-helberg.html$             "http://www.5694.com/168" permanent;
    rewrite ^/janebydesign1.html$             "http://www.5694.com/998" permanent;
    rewrite ^/pc2012.html$             "http://www.5694.com/h2012" permanent;
    rewrite ^/clientlist1.html$             "http://www.5694.com/1199" permanent;
    rewrite ^/revenges.html$             "http://www.5694.com/1162" permanent;
    rewrite ^/2-broke-girls.html$             "http://www.5694.com/1136" permanent;
    rewrite ^/glees3.html$             "http://www.5694.com/1147" permanent;
    
    rewrite ^/house08.html$             "http://www.5694.com/518" permanent;
    rewrite ^/panams01.html$             "http://www.5694.com/975" permanent;
    rewrite ^/usa.html$             "http://www.5694.com/h2011" permanent;
    rewrite ^/hitandmiss1.html$             "http://www.5694.com/1205" permanent;
    rewrite ^/topshef02.html$             "http://www.5694.com/1090" permanent;
    rewrite ^/antm18.html$             "http://www.5694.com/636" permanent;
    rewrite ^/mentalist4.html$             "http://www.5694.com/1123" permanent;
    rewrite ^/nikitas2.html$             "http://www.5694.com/1140" permanent;
    rewrite ^/tscs1.html$             "http://www.5694.com/1097" permanent;
    rewrite ^/tag/starz$             "http://www.5694.com/1181" permanent;
    rewrite ^/2011/02/10$             "http://www.5694.com/d2011" permanent;
    rewrite ^/fringe2011c.html$             "http://www.5694.com/613" permanent;
    rewrite ^/weed2011x.html$             "http://www.5694.com/853" permanent;
    rewrite ^/birth2011x.html$             "http://www.5694.com/1016" permanent;
    rewrite ^/tb2011x.html$             "http://www.5694.com/787" permanent;
    rewrite ^/mentalist2011c.html$             "http://www.5694.com/637" permanent;
    rewrite ^/supers7.html$             "http://www.5694.com/1137" permanent;
    rewrite ^/ct2011c.html$             "http://www.5694.com/679" permanent;
    rewrite ^/ga2011c.html$             "http://www.5694.com/651" permanent;
    rewrite ^/lie2011c.html$             "http://www.5694.com/846" permanent;
    rewrite ^/dh2011c.html$             "http://www.5694.com/629" permanent;
    rewrite ^/gg2011c.html$             "http://www.5694.com/361" permanent;
    rewrite ^/himym2011c.html$             "http://www.5694.com/827" permanent;
    rewrite ^/mf2011c.html$             "http://www.5694.com/655" permanent;
    rewrite ^/201205final.html$             "http://www.5694.com/d2012" permanent;
    rewrite ^/ncisla3.html$             "http://www.5694.com/1146" permanent;

    rewrite ^/covert-affairs-season-1.html$             "http://www.5694.com/440" permanent;
    rewrite ^/scandal1.html$             "http://www.5694.com/1134" permanent;
    rewrite ^/warehouse132011x.html$             "http://www.5694.com/910" permanent;
    rewrite ^/2brokes1.html$             "http://www.5694.com/1377" permanent;
    rewrite ^/ga7ost.html$             "http://www.5694.com/460" permanent;
    rewrite ^/communitys4.html$             "http://www.5694.com/1190" permanent;
    rewrite ^/ahs01.html$             "http://www.5694.com/909" permanent;

    rewrite ^/bigc2011x.html$             "http://www.5694.com/805" permanent;
    rewrite ^/csi12.html$                 "http://www.5694.com/1101" permanent;
    rewrite ^/smash1.html$                "http://www.5694.com/1129" permanent;
    rewrite ^/nurse4.html$                "http://www.5694.com/1201" permanent;
    rewrite ^/nikita2011c.html$           "http://www.5694.com/619" permanent;
    rewrite ^/2011/12/12$                 "http://www.5694.com/d2012"  permanent;
    rewrite ^/mfs01.html$		 "http://www.5694.com/342" permanent;

    rewrite ^/homelands01.html$ 	 "http://www.5694.com/917" permanent;
    rewrite ^/sherlock01.html$ 		 "http://www.5694.com/394" permanent;
    rewrite ^/downtons2.html$		 "http://www.5694.com/953" permanent;
    rewrite ^/sherlock02.html$ 		 "http://www.5694.com/959" permanent;
    rewrite ^/ggs05.html$ 		 "http://www.5694.com/1117" permanent;
    rewrite ^/2brokeg.html$		 "http://www.5694.com/1086" permanent;
    rewrite ^/tbbt2011c.html$ 		 "http://www.5694.com/638" permanent;
    rewrite ^/pll2011c.html$ 		 "http://www.5694.com/581" permanent;
    rewrite ^/tbbts5.html$ 		 "http://www.5694.com/1098" permanent;
    rewrite ^/downtons1.html$ 		 "http://www.5694.com/479" permanent;
    rewrite ^/gas08.html$ 		 "http://www.5694.com/1142" permanent;
    rewrite ^/lietome1.html$		 "http://www.5694.com/847" permanent;
    rewrite ^/gameofthrones2.html$ 	 "http://www.5694.com/1163" permanent;
    rewrite ^/game-of-thrones2011.html$  "http://www.5694.com/704" permanent;
    rewrite ^/vds3.html$		 "http://www.5694.com/1099" permanent;
    rewrite ^/shameless2011c.html$ 	 "http://www.5694.com/603" permanent;
    rewrite ^/vengeance.html$            "http://www.5694.com/1040" permanent;
    rewrite ^/raising-hope2011c.html$    "http://www.5694.com/640" permanent;
    rewrite ^/walkdeads02.html$          "http://www.5694.com/1006" permanent;
    rewrite ^/the-newsroom-season-1.html$ "http://www.5694.com/1233" permanent;
    rewrite ^/blackmirror.html$          "http://www.5694.com/1030" permanent;
    rewrite ^/shameless2.html$           "http://www.5694.com/992" permanent;
    rewrite ^/sherlock-2010.html$        "http://www.5694.com/433" permanent;

    rewrite ^/mfs3.html$            	 "http://www.5694.com/1145" permanent;
    rewrite ^/revenge.html$         	 "http://www.5694.com/1392" permanent;
    rewrite ^/grimms01.html$        	 "http://www.5694.com/1136" permanent;
    rewrite ^/cms07.html$           	 "http://www.5694.com/1126" permanent;
    rewrite ^/suits2011x.html$           "http://www.5694.com/774" permanent;
    rewrite ^/cm2011c.html$         	 "http://www.5694.com/1057" permanent;
    rewrite ^/the-newsroom-season-1-2.html$ "http://www.5694.com/1233" permanent;
    rewrite ^/bones7.html$          	 "http://www.5694.com/1131" permanent;
    rewrite ^/gfs03.html$           	 "http://www.5694.com/1063" permanent;
    rewrite ^/dropdeaddiva4.html$   	 "http://www.5694.com/1300" permanent;


    rewrite ^/revenges1.html$        "http://www.5694.com/1162" permanent;
    rewrite ^/tgw2011c.html$        "http://www.5694.com/639" permanent;
    rewrite ^/vds1.html$        "http://www.5694.com/358" permanent;
    rewrite ^/pois01.html$        "http://www.5694.com/1141" permanent;
    rewrite ^/dixies01.html$        "http://www.5694.com/1132" permanent;
    rewrite ^/vd2011c.html$        "http://www.5694.com/620" permanent;
    rewrite ^/tbs2.html$        "http://www.5694.com/96" permanent;
    rewrite ^/himyms7.html$        "http://www.5694.com/1114" permanent;
    rewrite ^/tbs1.html$        "http://www.5694.com/181" permanent;
    rewrite ^/bs2011c.html$        "http://www.5694.com/622" permanent;
    rewrite ^/prettyliars3.html$        "http://www.5694.com/1247" permanent;
    rewrite ^/tbs3.html$        "http://www.5694.com/445" permanent;
    rewrite ^/whitecollar2011x.html$        "http://www.5694.com/990" permanent;
    rewrite ^/thekilling2.html$        "http://www.5694.com/1185" permanent;
    rewrite ^/dhs08.html$        "http://www.5694.com/1106" permanent;
    rewrite ^/femme-fatales-season-1.html$        "http://www.5694.com/758" permanent;
    rewrite ^/glee2011c.html$        "http://www.5694.com/657" permanent;
    rewrite ^/epis2011c.html$        "http://www.5694.com/557" permanent;
    rewrite ^/missing1.html$        "http://www.5694.com/1135" permanent;
    rewrite ^/femme-fatales-season2.html$        "http://www.5694.com/1311" permanent;
    rewrite ^/ddd2011.html$        "http://www.5694.com/842" permanent;
    rewrite ^/in-treatment-3.html$        "http://www.5694.com/1280" permanent;
    rewrite ^/warehouse-13.html$        "http://www.5694.com/1308" permanent;
   
    rewrite ^/jmb$        "http://www.5694.com/d2013" permanent;
    rewrite ^/charlies01.html$        "http://www.5694.com/983" permanent;
    rewrite ^/csi-12.html$        "http://www.5694.com/1101" permanent;
    rewrite ^/ncis9.html$        "http://www.5694.com/1127" permanent;
    rewrite ^/meiju$        "http://www.5694.com/" permanent;
    rewrite ^/meiju.html$        "http://www.5694.com/" permanent;
    rewrite ^/sms.html$        "http://www.5694.com/1012" permanent;
    rewrite ^/spartacus1.html$        "http://www.5694.com/327" permanent;
    rewrite ^/tscs1.html$        "http://www.5694.com/1097" permanent;
    rewrite ^/killing1.html$        "http://www.5694.com/979" permanent;
    rewrite ^/ringers1.html$        "http://www.5694.com/1054" permanent;
    rewrite ^/newgirls01.html$        "http://www.5694.com/838" permanent;
    rewrite ^/suits-s02.html$        "http://www.5694.com/1282" permanent;
    rewrite ^/emily-vancamp.html$        "http://www.5694.com/s.php?k=%E8%89%BE%E7%B1%B3%E4%B8%BD" permanent;
    rewrite ^/page/(.*?)$        "http://www.5694.com/new_$1" permanent;
 
    rewrite ^/ultimate-spider-man-season-1.html$	/1242 permanent;
    rewrite ^/[^\.]+continuum-s01[^\.]+.html$	/1299 permanent;
    rewrite ^/unforges01.html$	/1096 permanent;
    rewrite ^/tbbtcf.html$	/1390 permanent;
    rewrite ^/onces1.html$	/1109 permanent;
    rewrite ^/playboys1.html$	/862 permanent;
    rewrite ^/sinbad.html$	/1304 permanent;
    rewrite ^/whitneys01.html$	/1052 permanent;
    rewrite ^/human2011c.html$	/118 permanent;
    rewrite ^/sanctuary-4.html$	/938 permanent;
    rewrite ^/richard-ii.html$	/1286 permanent;
    rewrite ^/houses08.html$	/1148 permanent;

    if ( $http_user_agent ~* (mobile|ucweb|MIDP|CECT|compal|LG-G|NEC-N|TCL-|Alcatel|Ericsson|bird|DAXIAN|DBTEL|EASTCOM|PANTECH|Dopod|Obigo|HAIER|kejian-|LENOVO-|BenQ-|MOT-|Nokia|SAMSUNG|SEC-|Panasonic-|Capitel-|SonyEricsson|SIE-|SHARP-|Amoi-|PANDA|ZTE-|Android|BlackBerry|iphone|ipad|ipod|skyfire) ) {
        rewrite ^(/.*?)$        http://m.5694.com$1     permanent;
    } 

    if ($http_host = 5694.com) {
       rewrite ^(.*) http://www.5694.com$1 permanent;
    }
    if ($http_host = yyse.org) {
       rewrite ^(.*) http://www.5694.com$1 permanent;
    }
    if ($http_host = www.yyse.org) {
       rewrite ^(.*) http://www.5694.com$1 permanent;
    }

    rewrite ^/([hd](\d+)()?(_\d+)?)$ "/h/l/$1.htm" last;
    rewrite ^/([a-z_]+(_hot)?(_\d+)?)$ "/h/l/$1.htm" last;

    rewrite ^/([a-z_]+_(tv|dianying)(_\d+)?)$	"/h/l/$1.htm"	last;

    rewrite ^/(\d+)$ "/h/a/$1.htm" last;
    rewrite ^/(\d+)p$ "/h/p/$1.htm" last;
    rewrite ^/x(\d+)$ "/h/x/$1.htm" last;
    rewrite ^/tpl.*$ "http://www.5694.com/" permanent; 
    
    rewrite ^/index2.htm$ "/index.php" last;
    rewrite ^/([a-z0-9_]+)$   "/h/l/$1.htm"   last;

    rewrite ^.*/files/(.*)$ /wp-includes/ms-files.php?file=$1 last;
    if (!-e $request_filename){
        rewrite ^.+?(/wp-.*) $1 last;
        rewrite ^.+?(/.*\.php)$ $1 last;
        #rewrite ^ /index.php last;
    }


    error_page  404   /404.html;
    access_log  /data/logs/yyse.log  access;
}
