#!/bin/bash

function configure_koha {
    sed -i "s#__ILS_DRIVER__#Koha#g" $mainconfig
    cp "$kohaconfig.dist" $kohaconfig
    read -e -p "Enter URL to your REST API endpoint (usually https://your-koha-server/api): " api
    sed -i "s#__API_URL__#$api#" $kohaconfig
    driverconfig=$kohaconfig
}

function configure_ncip {
    sed -i "s#__ILS_DRIVER__#NCIP#g" $mainconfig
    cp "$ncipconfig.dist" $ncipconfig
    read -e -p "Enter URL or IP address of your NCIP server: " server
    read -e -p "Enter AgencyId used for your institution: " agency
    sed -i "s#__NCIP_SERVER__#$server#" $ncipconfig
    sed -i "s#__NCIP_AGENCY__#$agency#" $ncipconfig
    driverconfig=$ncipconfig
}

function configure_sip {
    sed -i "s#__ILS_DRIVER__#SIP2#g" $mainconfig
    cp "$sip2config.dist" $sip2config
    read -e -p "Enter URL or IP address of your SIP2 server: " server
    read -e -p "Enter port: " port
    sed -i "s#__SIP_SERVER__#$server#" $sip2config
    sed -i "s#__SIP_PORT__#$port#" $sip2config
    driverconfig=$sip2config
}

function configure_demo {
    sed -i "s#__ILS_DRIVER__#Demo#g" $mainconfig
    cp "$democonfig.dist" $democonfig
    echo "No configuration needed for Demo ILS"
    driverconfig=$democonfig
}

dir=$(dirname $0)
configdir="$dir/config/autoload"
mainconfig="$configdir/tohu.local.php"
kohaconfig="$configdir/koha.local.php"
ncipconfig="$configdir/ncip.local.php"
sip2config="$configdir/sip2.local.php"
democonfig="$configdir/demo.local.php"

if [ -f $mainconfig ]; then
    echo
    read -e -p "Configuration file $mainconfig already exists, do you wish to create new one ? [Y/n] " yn

    if [ "$yn" == "n" ]; then
	exit 0
    fi
fi

cp "$mainconfig.dist" $mainconfig

echo "Tohu"
echo
echo "You are going to create configuration for your self-check"
echo
read -e -p "Enter name of your library: " library
echo
read -e -p "Enter name for your self-check station: " selfcheck
echo
read -e -p "Enter URL or path to your library logo: " logo

sed -i "s#__LIBRARY_NAME__#$library#g" $mainconfig
sed -i "s#__SELFCHECK_NAME__#$selfcheck#g" $mainconfig
sed -i "s#__LOGO__#$logo#g" $mainconfig

echo
echo "Which kind of connection/ILS would you like to use?"
echo "Possible options are:"
echo "1. Koha"
echo "2. NCIP"
echo "3. SIP2"
echo "4. Demo - for testing/demonstration purposes only"

while [[ ! $driver =~ ^[1-4]{1}$ ]]; do
    read -e -p "Enter your choice: " driver
done

driverconfig=""

if [ "$driver" == "1" ]; then
    configure_koha
elif [ "$driver" == "2" ]; then
    configure_ncip
elif [ "$driver" == "3" ]; then
    configure_sip
elif [ "$driver" == "4" ]; then
    configure_demo
fi

echo
echo "In which mode should this selfcheck operate?"
echo "Possible options are:"
echo "1. Info - shows only the patron's account information"
echo "2. Checkin - enable checking-in + shows patron's account informarion"
echo "3. Checkout - enable checking out + shows patron's account informarion"
echo "4. All"

while [[ ! $mode =~ ^[1-4]{1}$ ]]; do
    read -e -p "Enter your choice: " mode
done

if [ "$mode" == "1" ]; then
    mode="info"
elif [ "$mode" == "2" ]; then
    mode="checkin"
elif [ "$mode" == "3" ]; then
    mode="checkout"
elif [ "$mode" == "4" ]; then
    mode="all"
fi
sed -i "s#__SELFCHECK_MODE__#$mode#g" $mainconfig

echo "Configuration finished"
echo
echo "If you want do adjust the configuration later, you could edit these files:"
echo "Main config: $mainconfig"
echo "Driver config: $driverconfig"

