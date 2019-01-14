#!/bin/sh

#copy file first
# Control will enter here if $DIRECTORY doesn't exist.
cp -a ../config ./khomsn-klinic-suite/var/www/html/
cp -a ../doc ./khomsn-klinic-suite/var/www/html/
cp -a ../docform ./khomsn-klinic-suite/var/www/html/
cp -a ../image ./khomsn-klinic-suite/var/www/html/
cp -a ../jscss ./khomsn-klinic-suite/var/www/html/
cp -a ../libs ./khomsn-klinic-suite/var/www/html/
cp -a ../login ./khomsn-klinic-suite/var/www/html/
cp -a ../main ./khomsn-klinic-suite/var/www/html/
cp -a ../public ./khomsn-klinic-suite/var/www/html/

cp ../Change.log ./khomsn-klinic-suite/var/www/html/Change.log
cp ../index.html ./khomsn-klinic-suite/var/www/html/index.html
cp ../LICENSE ./khomsn-klinic-suite/var/www/html/LICENSE
cp ../README.md ./khomsn-klinic-suite/var/www/html/README.md
cp ../ReadMeFirst ./khomsn-klinic-suite/var/www/html/ReadMeFirst
cp ../version ./khomsn-klinic-suite/var/www/html/version

mv ./khomsn-klinic-suite/var/www/html/config/dbuspwd.php ./khomsn-klinic-suite/etc/khomsn/klinic/dbuspwd.php
mv ./khomsn-klinic-suite/var/www/html/config/recaptchakey.php ./khomsn-klinic-suite/etc/khomsn/klinic/recaptchakey.php
mv ./khomsn-klinic-suite/var/www/html/config/emailserver.php ./khomsn-klinic-suite/etc/khomsn/klinic/emailserver.php

#

packages="khomsn-klinic-suite"

echo -n "building packages:"
for f in $packages; do
	echo -n " ($f)"	
	( 
		cd $f; 
		fakeroot debian/rules binary >/dev/null;
		fakeroot debian/rules clean >/dev/null;
	) 
done

#remove file clean up
 rm -rf ./khomsn-klinic-suite/var/www/html/*
 rm -rf ./khomsn-klinic-suite/etc/khomsn/klinic/*
#
echo " done.  have fun :)"
