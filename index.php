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

					echo "<p>Informacje ogólne odnośnie dysków maszyn znajdują się
					<a href=\"index.php?page=vmdk\">tutaj</a></p>";

				
				} else {
			
					echo "<p>Informacje ogólne odnośnie maszyn znajdują się
					<a href=\"index.php?page=vms\">tutaj</a></p>";
				}
			}

			if ($_GET['page'] == 'main') {
			
				echo "	<p>
					<img id=\"ovfimg\" src=\"ovftool.png\" />
					<cap>VTMP</cap>, jest witryną gdzie można pobrać gotowe urządzenia
					wirtualne środowiska VirtualBox, z preinstalowanymi systemami 
					operacyjnymi. Powstanie tej witryny jest efektem frustracji twórcy,
					który praganie zaoszczędzić twój czas spędzony na tępym gapieniu
					się w ekran w oczekiwaniu na zainstalowanie systemu. Udostępnione
					tu maszyny są czystymi systemami, wyeksportowanymi zaraz po pierwszym
					uruchomieniu po instalacji, po upewnieniu się ze wszystko jest ok.
					Maszyny posiadają tyle pamięci RAM ile zasugerował VirtualBox. Może
					być jej więcej lub znacznie więcej, w zależności od tego ile będzie
					wymagał system do płynnej pracy. Ustawienia sieciowe są ustawione
					zawsze natywnie na NAT. Systemy operacyjne serwerowe z rodziny 
					GNU Linux, mają zawsze zainstalowane SSH, aby można było je od razu
					uruchamiać w trybie headless, oczywiście aby była możliwość połączenia
					się z maszyną należy odpowiednio skonfigurować sieć. Same dyski są
					również dostępne. Dyski maszyn dostępne są w formacie VMDK, ponieważ
					wypakowywane z formatu OVA wyeksportowanych maszyn. Jeśli system wymaga
					licencji, to maszyna wirtualna z danym system pojawi się jedynie wtedy
					gdy podczas instalacji nie będzie wymagany klucz licencji lub jego 
					aktywacja.</p>
					<p>
					Przygotowane maszyny są udostępnione tutaj bez gwarancji przydatności
					oraz mogą one się w ogóle nie zaimportować do Państwa środowisk
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
					<h3>Lista zmian dotycząca maszyn: </h3>
					<p>
						<ul>
							<li>2020-11-10 - Testowy wpis</li>
							<li>2020-11-10 - Ubuntu zrywa z architektura x86 (32-bit), od 18.04. 
								Więc przy maszynach tej dystrybucji od wersji 18.04 nie będzie oznaczeń architektury.
								Zatem wszystkie maszyny są 64-bitowe.</li>
						</ul>
					</p>
					<h3>Odnośnie danych logownia...</h3>
					<p>
						<ul>
							<li>Dla dystrybucji Linuxowych oraz systemów rodziny BSD oraz innych (jeśli dane logowania będą wymagane):<br />
								<br />
								<table>
									<tr><th>Rozdaj użytkownika</th><th>Nazwa użytkownika</th><th>Hasło</th></tr>
									<tr><td>Zwykły użytkownik</td><td>user</td><td>user1</td></tr>
									<tr><td>Administrator (root)</td><td>root</td><toor</td></tr>
								</table>
							</li>
							<br />
							<br />
							<li>Dla systemów Microsoft Windows:<br />
								<br />
								<table>
									<tr><th>Rodzaj systemu</th><th>Nazwa użytkownika</th><th>Hasło</th></tr>
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
						<h3>Zmiany odnosnie dysków maszyn</h3>
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
					<cap>Co należało by zmienić</cap> gdy korzysta się z maszyn udostępnionych na VTMP? 
					Maszyny tu udostępnione zostały skonfigurowane tak aby były jak najbardziej uniwersalne. 
					Ilość pamięci RAM została dostosowana do możliwości
					płynnej pracy na danym systemie operacyjnym, który został zobrazowany za pomocą danego urządzenia
					wirtualnego. Wielkości dysków jest dostosowywana na podstawie wymagań systemowych oraz brane jest
					również pod uwagę to że zazwyczaj użytkownik tworzy maszynę wirtualną w jakimś konkretnym celu,
					więc dodawana jest jescze dodatkowa przestrzeń użytkowa wynosząca 50% przestrzeni wymaganej przez
					system. Dystrybucje Linux-owe, w zależności od tego czy pracują domyślnie w trybie tekstowym a
					tryb graficzny jest opcjonalny, to brana jest pod uwagę wymagana wielkość dysku z uwzględnieniem
					nawet opcjonalnego trybu graficznego. Ostatnią rzeczą na jaką należy zwrócić uwagę są ustawienia
					sieci. Czy są one prawidłowe do naszego użytku, na pewno na systemach serwerowych, będzie to 
					niezbędne, aby można było korzystać z jakich kolwiek funkcji. 
					</p>
					<p>
					<h3>Co zrobić jeśli ilość miesca na dysku jest nie wystarczająca na nasze potrzeby lub 
					w trakcie pracy zabraknie nam wolnego miejsca na dysku?</h3>
					Możemy przenieść system na większy dysk, za pomocą takich narzędzi jak dd oraz gparted. Jednak
					wie rzeczy zależy od partycjonowania dysku. Jeśli system opiera się na prostym partycjonowaniu
					jak, wiekszość dystrybucji opartych na debianie z domyślną jedną partycja (wiekszość systemów
					tak będzie właśnie partycjonowana). Sposób jest prosty (<cap><strong>wykonujesze poniższe 
					działania na własne ryzyko</strong></cap>): 
					<ol>
						<li>Wyłączamy maszynę</li>
						<li>Tworzymy i podłączamy drugi dysk</li>
						<li>Uruchamiamy maszynę z LiveCD jakiejś dystrybucji umożliwiającej uruchomienie dd
						oraz zainstalowanie gparted</li>
						<li>Kopiujemy dysk 1 do 1 za pomocą dd</li>
						<li>Wielce prawdobne jest to że wolna przestrzeń na dysku będzie za partycją rozszerzoną
						z partycją swap, nic nie stoi na przeszkodzie że by usunąć je obie aby główna partycja
						(za pomocą gparted) miała dostęp do wolnego miejsca, przy rozszerzaniu musimy pamiętać
						o swap, po zakończeniu rozszerzania tworzymy po kolei partycję rozszerzoną w niej 
						partycję ze swap.</li>
						<li>Jeszcze na LiveCD montujemy partycję w dowolnym katalogu /mnt albo /media. Musimy
						zmienić UUID partycji swap, ponieważ to jest nowa partycja. Stara została usunięta. UUID
						możemy wyświetlić za pomocą polecenia <code>sudo blkid</code> w pisujemy nowy UUID do
						pliku w linii montowania swap-u. Zapisujemy, odmonotowujemy dyski, wyłączamy, wypinamy
						dyski, pod port 0 ustawiamy nasz nowy dysk i uruchamiamy maszynę</li>
					</ol>
					Jeśli jednak nie czujesz się na siłach aby wykonać powyższe polecenia, które wymgają nieco wprawy
					w pracy z dystrybucjami Linuxowymi, pozostaje Ci jedynie ściągnąć obraz systemu z oficjalnego źródła
					a następnie zainstalowanie go samodzielnie.
					</p>
					<p>";
					}
					if ( ($_GET['type'] == 'cli') || ( empty($_GET['type']) ) ) {
					echo "<h3>Dostosowywanie maszyn wirtualnych za pomocą wiersza poleceń.</h3>
						<p>
							<ol style=\"list-style-type: lower-latin\">
								<li>Dostosowanie ilości pamięci do możliwości komputera uruchamiającego maszynę<br />
									<code>vboxmanger modifyvm &lt;nazwa maszyny&gt; --memory &lt;ilość pamięci RAM w MB&gt;</code></li>
								<li>Ustawienie odpowidniego interfejsu dla maszyny z mostowanym dostępem do sieci<br />
									<code>vboxmanager modifyvm &lt;nazwa maszyny&gt; --bridgeadapter1 enp3s0*</code><br />
										<small>* - dla przykładu podano adapter z dystrybucji linuxowej, dla systemów
											MS Windows może być to coś w stylu \"Połączenie lokalne\"</small></li>
								<li>Dla, nie których systemów pamięć wideo o wielkości 8MB to definitywnie za mało, aby dodać
								trochę pamięci video wydajemy poniższe polecenie, uzupełniając je ilością przydzielanej
								pamięci<br />
									<code>vboxmanager modifyvm &lt;nazwa maszyny&gt; --vram &lt;ilość pamięci video&gt;</code>
								</li>
							</ol>
						</p>";
					}
					if ( ($_GET['type'] == 'gui') || ( empty($_GET['type']) ) ) {

						echo "<h3>Dostosowywanie maszyn wirtualnych za pomocą intefejsu graficznego.</h3>
							<p>
								Z racji tego iż na stronie jest nie wiele miejsca aby wyświetlić
								obraz przestawiający GUI VirtualBox w rozdzielczości umożliwiającej
								odczytanie danych z obrazka, zatem tutaj zostaną dodane linki, pod
								którymi można wyświetlić obrazy w pełnych rozmiaracha.
								<ol style=\"list-style-type: lower-latin\">
									<li>Dostosowywanie ilości pamięci do możliwości komputera uruchamiającego maszynę: 
										<a href=\"resources/screenShot_RAM.png\">Obraz</a></li>
									<li>Ustawienie odpowiedniego interfejsu dla maszyny z mostowanym dostępem do sieci: 
										<a href=\"resources/screenShot_bridgedadapter.png\">Obraz</a></li>
									<li>Dla, nie których systemów pamięć wideo o wielkości 8MB to definitywnie za mało,
										możemy zwiększyć ilość pamięci wideo za pomocą suwaka przedstawionego na
										obrazie, opisanego jako 'Pamięć wideo': <a href=\"resources/screenShot_vram.png\">Obraz</a></li>
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
						<li>2020-11-10 12:10 - Rozpoczęcie prac na VTMP</li>
						<li>2020-11-11 16:02 - Utworzorzenie większości funkcjonalności strony VTMP</li>
						<li>2020-11-13 23:24 - Zakończono frontend strony VTMP</li>
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
				<li><a href="index.php?page=main">Strona główna</a></li>
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
