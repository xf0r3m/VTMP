<!DOCTYPE html>
<html>
	<head>
		<meta chartset="utf-8" />
		<title>vtmp</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<div id="zero">	
		<div id="content">
		<?php
				function VMSList($filename, $query="") {

					if ( file($filename) ) {
						$lines=file($filename);
						if ( ! empty($_GET['page']) ) { echo "<ul class=\"vmlists\">"; }
						foreach ($lines as $line) {

							$lineDetails=explode(';', $line);
							if ( ! empty($_GET['page']) ) { 
								echo "<li><u><strong>" . $lineDetails[0] . ":</strong></u>
									<ul class=\"vmlists\">";
							}
							$filename2=trim($lineDetails[1]);
							$filepath=$filename2;
							$filename2=$filename2 . "/list.csv";
							if ( file($filename2) ) {
								$machines=file($filename2);
								foreach ($machines as $machine) {
							
									$machineDetails=explode(';', $machine);
									if ( ! empty($_GET['page']) ) {
									echo "<li>
										<table><tr>
											<td><img src=\"" . $machineDetails[0] . "\" /></td>
											<td><a href=\"" . $filepath . "/" . $machineDetails[2] . "\">" . $machineDetails[1] . "</a></td>
											<td>" . $machineDetails[3] . "</td><td>" . $machineDetails[4] . "</td>
											<td><small>(" . trim($machineDetails[5]) . ")</small></td>
										</tr></table>
									</li>";
									} else {	
										$machineDetails[5]=trim($machineDetails[5]);
										#echo $machineDetails[5] . "=" . $query . "<br />";

										if ( $machineDetails[5] === $query ) {
											echo "<div id=\"machinelink\">" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . "/" . $filepath . "/" . $machineDetails[2] . "</div>";
										}

									}


								}
							}
							if ( ! empty($_GET['page']) ) {
								echo "</ul></li>";
							}
						}
						if ( ! empty($_GET['page']) ) {
							echo "</ul>";
						}
					
					} 

				}

				function allList($filename, $query="") {

					if ( file($filename) ) {
						$categories=file($filename);
						foreach ($categories as $cat) {
						
							$catList=explode(';', $cat);
							if ( ! empty($_GET['page']) ) {
								echo "<p>";
								echo "<h3>" . $catList[0] . ":</h3>";
							}
							$filename1=trim($catList[1]);
							if ( empty($query) ) {
								VMSList($filename1);
							} else {
								VMSList($filename1, $query);
							}
							if ( ! empty($_GET['page']) ) {
								echo "</p>";
							}
						}
					}  
				}

			if ( ! empty($_GET['os']) ) {
	
				if ( $_GET['page'] == 'vmdk' ) {

					echo "<p>Informacje og??lne odno??nie dysk??w maszyn znajduj?? si??
					<a href=\"index.php?page=vmdk\">tutaj</a></p>";

				
				} else {
			
					echo "<p>Informacje og??lne odno??nie maszyn znajduj?? si??
					<a href=\"index.php?page=vms\">tutaj</a></p>";
				}
			}

			if ($_GET['page'] == 'main') {
			
				echo "	<p>
					<img id=\"ovfimg\" src=\"ovftool.png\" />
					<cap>VTMP</cap>, jest witryn?? gdzie mo??na pobra?? gotowe urz??dzenia
					wirtualne ??rodowiska VirtualBox, z preinstalowanymi systemami 
					operacyjnymi. Powstanie tej witryny jest efektem frustracji tw??rcy,
					kt??ry praganie zaoszcz??dzi?? tw??j czas sp??dzony na t??pym gapieniu
					si?? w ekran w oczekiwaniu na zainstalowanie systemu. Udost??pnione
					tu maszyny s?? czystymi systemami, wyeksportowanymi zaraz po pierwszym
					uruchomieniu po instalacji, po upewnieniu si?? ze wszystko jest ok.
					Maszyny posiadaj?? tyle pami??ci RAM ile zasugerowa?? VirtualBox. Mo??e
					by?? jej wi??cej lub znacznie wi??cej, w zale??no??ci od tego ile b??dzie
					wymaga?? system do p??ynnej pracy. Ustawienia sieciowe s?? ustawione
					zawsze natywnie na NAT. Systemy operacyjne serwerowe z rodziny 
					GNU Linux, maj?? zawsze zainstalowane SSH, aby mo??na by??o je od razu
					uruchamia?? w trybie headless, oczywi??cie aby by??a mo??liwo???? po????czenia
					si?? z maszyn?? nale??y odpowiednio skonfigurowa?? sie??. Same dyski s??
					r??wnie?? dost??pne. Dyski maszyn dost??pne s?? w formacie VMDK, poniewa??
					wypakowywane z formatu OVA wyeksportowanych maszyn. Je??li system wymaga
					licencji, to maszyna wirtualna z danym system pojawi si?? jedynie wtedy
					gdy podczas instalacji nie b??dzie wymagany klucz licencji lub jego 
					aktywacja.</p>
					<p>
					Przygotowane maszyny s?? udost??pnione tutaj bez gwarancji przydatno??ci
					oraz mog?? one si?? w og??le nie zaimportowa?? do Pa??stwa ??rodowisk
					wirtualnych.
					</p>";
					

			} else if ( ( ($_GET['page'] == 'vms') || ($_GET['page'] == 'vmdk') )&& (! empty($_GET['os']) ) ) {

				if ( $_GET['page'] == 'vms' ) { $prefix='vms'; }
				else { $prefix = 'vmdk'; }

				switch($_GET['os']) {

					case 'bsd': echo "<h3>Systemy rodziny BSD:</h3>";
							$suffix='bsd/systems.csv';
							break;
					case 'linux': echo "<h3>Dystrybucje Linuxowe: </h3>";
							$suffix='linux/distros.csv';
							break;
					case 'windows': echo "<h3>MS Windows: </h3>";
							$suffix='windows/versions.csv';
							break;
					case 'others': echo "<h3>Inne systemy: </h3>";
							$suffix='others/systems.csv';
							break;
				}

				$filename=$prefix . '/' . $suffix;
				VMSList($filename);
			
			} else if ($_GET['page'] == 'vms') {
				echo "<div id=\"changelogvms\">
					<h3>Lista zmian dotycz??ca maszyn: </h3>
					<p>
						<ul>
							<li>2020-11-10 - Testowy wpis</li>
							<li>2020-11-10 - Ubuntu zrywa z architektura x86 (32-bit), od 18.04. 
								Wi??c przy maszynach tej dystrybucji od wersji 18.04 nie b??dzie oznacze?? architektury.
								Zatem wszystkie maszyny s?? 64-bitowe.</li>
						</ul>
					</p>
					<h3>Odno??nie danych logownia...</h3>
					<p>
						<ul>
							<li>Dla dystrybucji Linuxowych oraz system??w rodziny BSD oraz innych (je??li dane logowania b??d?? wymagane):<br />
								<br />
								<table>
									<tr><th>Rozdaj u??ytkownika</th><th>Nazwa u??ytkownika</th><th>Has??o</th></tr>
									<tr><td>Zwyk??y u??ytkownik</td><td>user</td><td>user1</td></tr>
									<tr><td>Administrator (root)</td><td>root</td><toor</td></tr>
								</table>
							</li>
							<br />
							<br />
							<li>Dla system??w Microsoft Windows:<br />
								<br />
								<table>
									<tr><th>Rodzaj systemu</th><th>Nazwa u??ytkownika</th><th>Has??o</th></tr>
									<tr><td>Windowsy desktopowe</td><td>Admin</td><td>(brak)</td></tr>
									<tr><td>Windowsy serwerowe</td><td>Administrator</td><td>Qwertyuiop1@</td></tr>
								</table>
							</li>
						</ul>
					</p>
					<p>
					<h3>Oznaczenia danych w tabeli</h3>
						<table>
							<tr>
								<th>Ikona</th>
								<th>Nazwa maszyny/link do pobrania</th>
								<th>Rozmiar<th><th>Data utworzenia</th>
								<th>Oznaczenie dla VTMPc</th>
							</tr>
						</table>
					</p>
					</div>
					<div id=\"allvmlist\">";

						$filename='vms/list.csv';
						allList($filename);
						
					echo "</div>";

			} else if ($_GET['page'] == 'vmdk') { 
			
				echo "<div id=\"changelogvmdk\">
						<p>
						<h3>Zmiany odnosnie dysk??w maszyn</h3>
							<ul>
								<li>2020-11-11 11:37 - Testowy wpis</li>
							</ul>
						</p>
					</div>
					<div id=\"alldiskslist\">";
						$filename='vmdk/list.csv';
						allList($filename);
					echo "</div>";	
			
			} else if ($_GET['page'] == 'adjust') {
				if ( empty($_GET['type']) ) {
				echo "
					<p>
					<img id=\"adjust_icon\" src=\"programming.png\" />
					<cap>Co nale??a??o by zmieni??</cap> gdy korzysta si?? z maszyn udost??pnionych na VTMP? 
					Maszyny tu udost??pnione zosta??y skonfigurowane tak aby by??y jak najbardziej uniwersalne. 
					Ilo???? pami??ci RAM zosta??a dostosowana do mo??liwo??ci
					p??ynnej pracy na danym systemie operacyjnym, kt??ry zosta?? zobrazowany za pomoc?? danego urz??dzenia
					wirtualnego. Wielko??ci dysk??w jest dostosowywana na podstawie wymaga?? systemowych oraz brane jest
					r??wnie?? pod uwag?? to ??e zazwyczaj u??ytkownik tworzy maszyn?? wirtualn?? w jakim?? konkretnym celu,
					wi??c dodawana jest jescze dodatkowa przestrze?? u??ytkowa wynosz??ca 50% przestrzeni wymaganej przez
					system. Dystrybucje Linux-owe, w zale??no??ci od tego czy pracuj?? domy??lnie w trybie tekstowym a
					tryb graficzny jest opcjonalny, to brana jest pod uwag?? wymagana wielko???? dysku z uwzgl??dnieniem
					nawet opcjonalnego trybu graficznego. Ostatni?? rzecz?? na jak?? nale??y zwr??ci?? uwag?? s?? ustawienia
					sieci. Czy s?? one prawid??owe do naszego u??ytku, na pewno na systemach serwerowych, b??dzie to 
					niezb??dne, aby mo??na by??o korzysta?? z jakich kolwiek funkcji. 
					</p>
					<p>
					<h3>Co zrobi?? je??li ilo???? miesca na dysku jest nie wystarczaj??ca na nasze potrzeby lub 
					w trakcie pracy zabraknie nam wolnego miejsca na dysku?</h3>
					Mo??emy przenie???? system na wi??kszy dysk, za pomoc?? takich narz??dzi jak dd oraz gparted. Jednak
					wie rzeczy zale??y od partycjonowania dysku. Je??li system opiera si?? na prostym partycjonowaniu
					jak, wiekszo???? dystrybucji opartych na debianie z domy??ln?? jedn?? partycja (wiekszo???? system??w
					tak b??dzie w??a??nie partycjonowana). Spos??b jest prosty (<cap><strong>wykonujesze poni??sze 
					dzia??ania na w??asne ryzyko</strong></cap>): 
					<ol>
						<li>Wy????czamy maszyn??</li>
						<li>Tworzymy i pod????czamy drugi dysk</li>
						<li>Uruchamiamy maszyn?? z LiveCD jakiej?? dystrybucji umo??liwiaj??cej uruchomienie dd
						oraz zainstalowanie gparted</li>
						<li>Kopiujemy dysk 1 do 1 za pomoc?? dd</li>
						<li>Wielce prawdobne jest to ??e wolna przestrze?? na dysku b??dzie za partycj?? rozszerzon??
						z partycj?? swap, nic nie stoi na przeszkodzie ??e by usun???? je obie aby g????wna partycja
						(za pomoc?? gparted) mia??a dost??p do wolnego miejsca, przy rozszerzaniu musimy pami??ta??
						o swap, po zako??czeniu rozszerzania tworzymy po kolei partycj?? rozszerzon?? w niej 
						partycj?? ze swap.</li>
						<li>Jeszcze na LiveCD montujemy partycj?? w dowolnym katalogu /mnt albo /media. Musimy
						zmieni?? UUID partycji swap, poniewa?? to jest nowa partycja. Stara zosta??a usuni??ta. UUID
						mo??emy wy??wietli?? za pomoc?? polecenia <code>sudo blkid</code> w pisujemy nowy UUID do
						pliku w linii montowania swap-u. Zapisujemy, odmonotowujemy dyski, wy????czamy, wypinamy
						dyski, pod port 0 ustawiamy nasz nowy dysk i uruchamiamy maszyn??</li>
					</ol>
					Je??li jednak nie czujesz si?? na si??ach aby wykona?? powy??sze polecenia, kt??re wymgaj?? nieco wprawy
					w pracy z dystrybucjami Linuxowymi, pozostaje Ci jedynie ??ci??gn???? obraz systemu z oficjalnego ??r??d??a
					a nast??pnie zainstalowanie go samodzielnie.
					</p>
					<p>";
					}
					if ( ($_GET['type'] == 'cli') || ( empty($_GET['type']) ) ) {
					echo "<h3>Dostosowywanie maszyn wirtualnych za pomoc?? wiersza polece??.</h3>
						<p>
							<ol style=\"list-style-type: lower-latin\">
								<li>Dostosowanie ilo??ci pami??ci do mo??liwo??ci komputera uruchamiaj??cego maszyn??<br />
									<code>vboxmanger modifyvm &lt;nazwa maszyny&gt; --memory &lt;ilo???? pami??ci RAM w MB&gt;</code></li>
								<li>Ustawienie odpowidniego interfejsu dla maszyny z mostowanym dost??pem do sieci<br />
									<code>vboxmanager modifyvm &lt;nazwa maszyny&gt; --bridgeadapter1 enp3s0*</code><br />
										<small>* - dla przyk??adu podano adapter z dystrybucji linuxowej, dla system??w
											MS Windows mo??e by?? to co?? w stylu \"Po????czenie lokalne\"</small></li>
								<li>Dla, nie kt??rych system??w pami???? wideo o wielko??ci 8MB to definitywnie za ma??o, aby doda??
								troch?? pami??ci video wydajemy poni??sze polecenie, uzupe??niaj??c je ilo??ci?? przydzielanej
								pami??ci<br />
									<code>vboxmanager modifyvm &lt;nazwa maszyny&gt; --vram &lt;ilo???? pami??ci video&gt;</code>
								</li>
							</ol>
						</p>";
					}
					if ( ($_GET['type'] == 'gui') || ( empty($_GET['type']) ) ) {

						echo "<h3>Dostosowywanie maszyn wirtualnych za pomoc?? intefejsu graficznego.</h3>
							<p>
								Z racji tego i?? na stronie jest nie wiele miejsca aby wy??wietli??
								obraz przestawiaj??cy GUI VirtualBox w rozdzielczo??ci umo??liwiaj??cej
								odczytanie danych z obrazka, zatem tutaj zostan?? dodane linki, pod
								kt??rymi mo??na wy??wietli?? obrazy w pe??nych rozmiaracha.
								<ol style=\"list-style-type: lower-latin\">
									<li>Dostosowywanie ilo??ci pami??ci do mo??liwo??ci komputera uruchamiaj??cego maszyn??: 
										<a href=\"resources/screenShot_RAM.png\">Obraz</a></li>
									<li>Ustawienie odpowiedniego interfejsu dla maszyny z mostowanym dost??pem do sieci: 
										<a href=\"resources/screenShot_bridgedadapter.png\">Obraz</a></li>
									<li>Dla, nie kt??rych system??w pami???? wideo o wielko??ci 8MB to definitywnie za ma??o,
										mo??emy zwi??kszy?? ilo???? pami??ci wideo za pomoc?? suwaka przedstawionego na
										obrazie, opisanego jako 'Pami???? wideo': <a href=\"resources/screenShot_vram.png\">Obraz</a></li>
								</ol>
							</p>";
					
					}
					if ( empty($_GET['type']) )  {
					echo "<p>
						Icons made by <a href=\"https://www.flaticon.com/authors/flat-icons\" title=\"Flat Icons\">
						Flat Icons</a> from <a href=\"https://www.flaticon.com/\" title=\"Flaticon\"> www.flaticon.com</a>
					<p>";
					}

			} else if ($_GET['page'] == 'changelog') { 

				echo "<h3>Lista zmian</h3>
					<p>
					<ul>
						<li>2020-11-10 12:10 - Rozpocz??cie prac na VTMP</li>
						<li>2020-11-11 16:02 - Utworzorzenie wi??kszo??ci funkcjonalno??ci strony VTMP</li>
						<li>2020-11-13 23:24 - Zako??czono frontend strony VTMP</li>
					</ul>
					</p>";
			
			} else {

				if ( ! empty($_GET['qvm']) ) {

					$fname='vms/list.csv';
					allList($fname, $_GET['qvm']);
				} else if ( ! empty($_GET['qvmdk'])) {
				       	$fname='vmdk/list.csv';
					allList($fname, $_GET['qvmdk']);
				} else {

					header("Location: index.php?page=main");
				}
			}
		?>
		</div>
		<div id="menu">
			<ul id="mainmenu">
				<li><a href="index.php?page=main">Strona g????wna</a></li>
				<li>
					<a href="index.php?page=vms">Maszyny wirtualne</a>
					<?php
						if ($_GET['page'] == 'vms') {
							echo "<ul class=\"secondmenu\">
								<li><a href=\"index.php?page=vms&os=bsd\">BSD</a></li>
								<li><a href=\"index.php?page=vms&os=linux\">Linux</a></li>
								<li><a href=\"index.php?page=vms&os=windows\">Windows</a></li>
								<li><a href=\"index.php?page=vms&os=others\">Inne</a></li>
								</ul>";
						}
					?>
				</li>
				<li><a href="index.php?page=vmdk">Dyski maszyn wirtualnych</a>
					<?php
						if ($_GET['page'] == 'vmdk') {
							echo "<ul class=\"secondmenu\">
								<li><a href=\"index.php?page=vmdk&os=bsd\">BSD</a></li>
								<li><a href=\"index.php?page=vmdk&os=linux\">Linux</a></li>
								<li><a href=\"index.php?page=vmdk&os=windows\">Windows</a></li>
								<li><a href=\"index.php?page=vmdk&os=others\">Inne</a></li>
							</ul>";

						}		
					?>
				</li>
				<li>
					<a href="index.php?page=adjust">Dostosowywanie maszyny</a>
					<?php
						if ($_GET['page'] == 'adjust') {
							echo "<ul class=\"secondmenu\">
								<li><a href=\"index.php?page=adjust&type=cli\">vboxmanage</a></li>
								<li><a href=\"index.php?page=adjust&type=gui\">VirtualBox GUI</a></li>
								</ul>";
						}
					?>
				</li>
				<li><a href="index.php?page=changelog">Lista zmian</a></li>
			</ul>
		</div>
		</div>
	</body>
</html>
