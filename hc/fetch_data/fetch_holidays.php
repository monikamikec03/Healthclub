<?php
require("../../../moj_spoj/otvori_vezu_cmp.php");

$year = date('Y') + 1;

$api_key = "0dbfe6900ce94db1ee9a8aaca399c84c0b366211";
$country_code = "HR";

$url = "https://calendarific.com/api/v2/holidays?api_key=$api_key&country=$country_code&year=$year&language=hr";

$data = file_get_contents($url);
$json_data = json_decode($data, true);

$holidays = $json_data['response']['holidays'];

foreach ($holidays as $holiday) {
    $date = $holiday['date']['iso'];
    $name = translateHolidayName($holiday['name']);

    echo $date . " ";
    echo $name;
    echo "<br>";

    $sql = "INSERT INTO praznici (datumPraznika, nazivPraznika) VALUES('$date', '$name')";
    mysqli_query($veza, $sql);
    


}





function translateHolidayName($name) {
    switch ($name) {
        case "New Year's Day":
            return "Nova godina";
        case "Epiphany":
            return "Sveta Tri Kralja";
        case "Orthodox Christmas Day":
            return "Pravoslavni Božić";
        case "Međimurje Unification Day":
            return "Dan Međimurske županije";
        case "Day of the International Recognition of the Republic of Croatia":
            return "Dan međunarodnog priznanja Republike Hrvatske";
        case "Day of Peaceful Reintegration of the Croatian Danube Region":
            return "Dan mirne reintegracije hrvatskog Podunavlja";
        case "Day of the Establishment of the National Protection in the Republic of Croatia":
            return "Dan osnivanja Domovinske zaštite u Republici Hrvatskoj";
        case "Father’s Day":
            return "Dan očeva";
        case "March Equinox":
            return "Osmišljeni dan";
        case "Ramadan Start":
            return "Početak Ramazana";
        case "Easter Sunday":
            return "Uskrs";
        case "Easter Monday":
            return "Uskrsni ponedjeljak";
        case "Orthodox Easter Day":
            return "Pravoslavni Uskrs";
        case "Orthodox Easter Monday":
            return "Pravoslavni uskrsni ponedjeljak";
        case "Ramadan Bayram":
            return "Ramazanski Bajram";
        case "Day of the Deaths of Zrinski and Frankopan":
            return "Dan smrti Zrinskih i Frankopana";
        case "Labor Day / May Day":
            return "Praznik rada";
        case "Europe Day and Victory Day Over Fascism":
            return "Dan Europe i Dan pobjede nad fašizmom";
        case "Mother’s Day":
            return "Majčin dan";
        case "Statehood Day":
            return "Dan državnosti";
        case "Corpus Christi":
            return "Tijelovo";
        case "June Solstice":
            return "Ljetni solsticij";
        case "Day of Antifascist Struggle":
            return "Dan antifašističke borbe";
        case "Independence Day":
            return "Dan neovisnosti";
        case "Kurban Bayram":
            return "Kurban bajram";
        case "Victory Day":
            return "Dan pobjede";
        case "Assumption of Mary":
            return "Velika Gospa";
        case "European Day of Remembrance of Victims of Totalitarian and Authoritarian Regimes":
            return "Europski dan sjećanja na žrtve totalitarnih režima";
        case "Day of Remembrance of Missing Persons in the Homeland War":
            return "Dan sjećanja na nestale osobe u Domovinskom ratu";
        case "Rosh Hashana":
            return "Roš Hašana";
        case "September Equinox":
            return "Jesenski ekvinocij";
        case "Yom Kippur":
            return "Jom Kipur";
        case "Unification Day of Istria, Rijeka, Zadar, and the Islands":
            return "Dan ujedinjenja Istre, Rijeke, Zadra i otoka";
        case "Day of the Croatian Parliament":
            return "Dan Hrvatskog sabora";
        case "All Saints' Day":
            return "Svi sveti";
        case "Remembrance Day":
            return "Dan sjećanja";
        case "December Solstice":
            return "Zimski solsticij";
        case "Christmas Day":
            return "Božić";
        case "St Stephen's Day":
            return "Sveti Stjepan";
        }
    }

?>