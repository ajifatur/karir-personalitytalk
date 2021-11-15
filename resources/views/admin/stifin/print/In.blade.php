@extends('stifin/print/layout')

@section('description')
		<div class="description">
			<p class="mb-1 font-weight-bold" style="font-size: 16px;">Intisari tentang {{ $stifin->name }} :</p>
			<p>“Memiliki Mesin Kecerdasan Insting, yang berarti merujuk pada naluri sebagai indera ketujuh {{ $stifin->name }} yang dilengkapi dengan kemampuan serba bisa. Kecerdasan tersebut dikemudikan secara otomatis sehingga membuat {{ $stifin->name }} lebih responsive, mudah beradaptasi dan banyak kawan.”</p>
			<p class="mb-1 font-weight-bold" style="font-size: 16px;">Kemistri :</p>
			<p>Tipe kepribadian ini memiliki kemistri atau “ngeklik” secara alami  untuk “Selalu BAHAGIA”, artinya {{ $stifin->name }} selalu mendambakan kehidupan yang bahagia, tenang, seimbang, damai dan penuh ketentraman. {{ $stifin->name }} selalu mencari suasana yang penuh cinta, kasih sayang dan harmoni. Kebutuhan ini membuat {{ $stifin->name }} sangat pandai membangun suasana dan beradaptasi dengan suasana apapun. {{ $stifin->name }} juga selalu mampu menstabilkan emosinya dengan lebih baik, sehingga bisa menjadi pribadi yang menyenangkan dan disukai banyak orang. Jika {{ $stifin->name }} mampu memperkuat potensi ini, maka di masa depan {{ $stifin->name }} akan mudah bekerjasama, mudah mendapatkan kepercayaan dan mudah diterima oleh siapapun.<br/>
Untuk bisa mewujudkan kondisi ideal ini di masa depan, {{ $stifin->name }} dituntut untuk selalu berlatih menjaga stabilitas emosi, membangun empati, cara bergaul yang baik, komunikasi interpersonal dan memperbanyak jaringan pertemanan . PR terbesar {{ $stifin->name }} adalah belajar untuk bisa menjadi pribadi yang jujur, terpercaya, suka menolong dan senang menjalin hubungan pertemanan secara luas.</p>
			<p class="mb-1 font-weight-bold" style="font-size: 16px;">Pahami Daya Dukung :</p>
			<p>
				Agar dapat mewujudkan kehidupan yang terbaik di masa depan, {{ $stifin->name }} perlu mengetahui dan memahami beberapa daya dukung yang penting bagi {{ $stifin->name }} sebagai panduan dan rambu-rambu untuk “mengasah diri” yaitu :
				<br/>
				<ol>
					<li>Sistem Operasi Otak Dominan berada di Belahan Otak Tengah.<br/>
Menunjukkan bagian otak yang paling aktif dalam memproses data. Bagian otak ini identik dengan Otak Bagian Tengah (Reptilian Brain). Bagian otak ini fungsi dominannya adalah memberikan respon emosional secara cepat, mudah membalas, namun mudah reda juga, menjaga keseimbangan, cepat mahir belajar segala hal, spiritualitas yang tinggi dan kesediaan berkorban yang sangat besar. Respon otomatisnya lebih cepat dari jalan pikirannya.</li>
					<li>Jenis Kecerdasan : AQ (Altruist Quotient)<br/>
Secara umum {{ $stifin->name }} memiliki empati dan kepedulian yang tinggi terhadap orang lain. Ia bahkan rela berkorban demi kebahagiaan orang lain. Modalitas ini perlu dirawat, dijaga dan terus dilatih untuk mendukung {{ $stifin->name }} agar makin siap menjadi seorang pribadi  yang disukai, dicari dan dipercaya oleh banyak orang.</li>
					<li>Peranan : Partner (Mitra)<br/>
{{ $stifin->name }} sangat menyenangkan dalam sebuah kerjasama. Pribadinya yang terpercaya membuat {{ $stifin->name }} banyak mendapatkan peluang, kesempatan dan tawaran-tawaran menarik dari teman-temannya. Karakter {{ $stifin->name }} yang ingin menjaga kepercayaan, membuatnya semakin disukai dan dicari.</li>
					<li>Kelebihan : 3rd eye (Mata Ketiga)<br/>
{{ $stifin->name }} memiliki naluri yang kuat dalam merespon lingkungan. Respon ini muncul begitu saja, spontan, tanpa proses berfikir. Sering disebut indera ketujuh, artinya ia dapat melihat sesuatu yang tidak dapat dilihat orang lain karena kecerdasan instingtifnya. Pengalaman yang makin matang membuat nalurinya semakin tajam.</li>
					<li>Target : Growth (Pertumbuhan)<br/>
Harapan terbesar {{ $stifin->name }} adalah mampu menciptakan pertumbuhan, perkembangan dan peningkatan hasil secara berkelanjutan. Untuk mendapatkan itu semua {{ $stifin->name }} bersedia bekerja lebih keras, belajar lebih giat dan berlatih lebih banyak  untuk memahami kunci-kunci sukses di berbagai bidang.</li>
					<li>Harapan : Contributing (Kontribusi)<br/>
{{ $stifin->name }} sangat bersemangat untuk menjadi pribadi yang bermanfaat bagi orang lain. Ia selalu ingin memberi, berbagi dan membantu siapapun yang membutuhkan bantuannya. Kontribusinya selalu lebih besar dari siapapun, baik dari tenaga, waktu, pikiran atau uang, sehingga ia selalu disukai banyak orang.</li>
					<li>Arah Merek : Merek pada Tingkat Keberperanannya<br/>
Orang semakin mengenal dan mengakui {{ $stifin->name }} jika ia mampu meningkatkan level perannya secara lebih luas. {{ $stifin->name }} harus terus menunjukkan peran-peran yang lebih besar di berbagai bidang yang diterjuninya. Semakin banyak kesuksesan peran yang ia alami, semakin tinggi pengakuan orang kepadanya.</li>
					<li>Tabiat terhadap Uang : Penolong<br/>
{{ $stifin->name }} sangat senang mengumpulkan uang agar bisa menolong sebanyak mungkin orang-orang di sekitarnya.  Ia kurang bisa menikmati kekayaan, jika di sekitarnya ia masih melihat begitu banyak orang yang menderita. Kekuatan ini sekaligus menjadi kelemahannya yang sering dengan mudah dimanfaatkan oleh orang lain.</li>
					<li>Konstitusi (bentuk) Jasmani : Stenis atau datar.<br/>
Secara umum {{ $stifin->name }} didukung bentuk tubuh yang ramping dan datar. Tidak gempal dan kekar.</li>
					<li>Kekuatan Jasmani : Adaptasi Fisik<br/>
Menunjukkan kekuatan tubuh {{ $stifin->name }} ditopang dengan adanya kombinasi antara kekuatan, kelenturan dan sendi yang kokoh, membuatnya dinamis dan bisa bergerak dengan lincah. </li>
					<li>Fungsi Tubuh : Serba Bisa<br/>
{{ $stifin->name }} memiliki konstruksi tulang kuat dan lentur, sehingga membantu dorongan sikap dinamis yang ingin melakukan apa saja dengan segera.</li>
					<li>Personality Tetap yang dapat diriset secara Psikometrik :<br/>
Artinya : Jenis kepribadian atau karakter permanen yang melekat pada diri {{ $stifin->name }} yang jika dilakukan tes psikometrik hasilnya sama adalah :<br/>
						<ol style="list-style-type: lower-latin;">
							<li>Balanced: penuh keseimbangan</li>
							<li>Compromising : mengkompromikan</li>
							<li>Peaceful : tenang, penuh kedamaian</li>
							<li>Resourceful : pandai, banyak akal, banyak ide</li>
							<li>Simple : berfikir sederhana, tidak rumit</li>
							<li>Forgiving: mudah memaafkan</li>
							<li>Occupied : sibuk, senang dengan aktivitas</li>
							<li>Flowing : mengalir begitu saja</li>
							<li>Smooth : halus, polos</li>
							<li>Intermediary : peran perantara</li>
						</ol>
					</li>
				</ol>
			</p>
			<p class="mb-1 font-weight-bold" style="font-size: 16px;">Empat Kata Kunci :</p>
			<p>
				<ol>
					<li>Merasakan : kemampuan memahami dan merasakan adalah kekuatan {{ $stifin->name }}.</li>
					<li>Pernafasan : pernafasan yang baik, sehat dan stabil perlu {{ $stifin->name }} jaga.</li>
					<li>Memimpin : dorongan memimpin dengan kebijaksanaan  perlu banyak dilatih.</li>
					<li>Dicintai : disukai, disayang dan dicintai menjadi keistimewaan  {{ $stifin->name }}.</li>
				</ol>
			</p>
			<p class="mb-1 font-weight-bold" style="font-size: 16px;">Aspek Belajar :</p>
			<p>
				<b>Cara Belajar :</b><br/>Prestasi belajar secara akademik bisa melejit jika {{ $stifin->name }} mau memilih dan melakukan tips-tips belajar yang sesuai dengan mesin kecerdasan {{ $stifin->name }}, yaitu :
				<ol>
					<li>Merangkai bacaan menjadi rangkuman dan mengurai kembali sampai tuntas, gunakan latar music.</li>
				</ol>
				<b>Meningkatkan Minat Belajar : </b><br/>
				Hilangkan tekanan dengan scaffolding (dibimbing dari dekat).<br/>
				Artinya : Untuk mendongkrak semangat belajar, hilangkan situasi yang menekan atau membuatnya takut. Tenangkan dengan sikap membimbing, mendukung dan menemaninya belajar secara intensif.
			</p>
			<p class="mt-3 mb-1 font-weight-bold" style="font-size: 16px;">Klu Diri :</p>
			<p>
				Klu Diri adalah “kata kunci”, “ciri khas” atau “rujukan utama” yang perlu diperhatikan, dipertimbangkan dan dijadikan pedoman dalam mengambil keputusan terhadap SEKOLAH, PROFESI dan BISNIS pada diri {{ $stifin->name }} yaitu :<br/>
				“Mencari PERAN untuk Me-multitasking-kan Pengabdiannya.”<br/>
				Artinya : 
				{{ $stifin->name }} harus mencari berbagai kegiatan, peran dan tanggung jawab, baik di sekolah maupun organisasi, agar peran positifnya semakin luas. 
				<ul>
					<li>Jika SEKOLAH : cari sekolah yang memiliki banyak program minat dan bakat, organisasi sosialnya maju, organisasi intranya maju dan banyak prestasi serta program pengabdian kepada masyarakat.</li>
					<li>Jika PROFESI : pilih profesi yang membuatnya berharga, bisa membantu dan menolong orang lain serta menjadi solusi bagi orang-orang membutuhkan.</li>
					<li>Jika BISNIS : cari dan pilihlah jenis bisnis yang {{ $stifin->name }} sukai, memiliki kontribusi nyata dalam kehidupan, berprospek bagus serta memiliki pasar yang terus tumbuh.</li>

				</ul>
			</p>
			<p class="mt-3 mb-1 font-weight-bold" style="font-size: 16px;">Personal Brand {{ $stifin->name }} :</p>
			<p>
				Jika semua proses di atas bisa {{ $stifin->name }} lalui dengan sabar dan konsisten, maka {{ $stifin->name }} bisa menjadi pribadi yang dikenal sebagai :<br/>
				<table width="100%" border="1">
					<tr>
						<td>Penyeimbang Paling Adaptif</td>
						<td>Pengakses Terbaik</td>
						<td>Tangan Kanan Serba Bisa</td>
						<td>Penyambung Kepentingan</td>
						<td>Spiritualis Terjujur</td>
					</tr>
					<tr>
						<td>Ensiklopedik Terlengkap</td>
						<td>Aktivis Paling Murni</td>
						<td>Naluri Paling Tajam</td>
						<td>Pencari Damai</td>
						<td>Pemeduli Paling Berkorban</td>
					</tr>
				</table>
			</p>
			<p class="mt-3 mb-1 font-weight-bold" style="font-size: 16px;">Personal Benchmark :</p>
			<p>
				Tokoh-tokoh yang bisa {{ $stifin->name }} tiru sisi positifnya sebagai model antara lain : Tukul Arwana, Sony Dwi Kuncoro, Ahmad Syafi’I Ma’arif, Megawati Soekarno Putri, Anggun C. Sasmi, Khofifah Indar Parawansa, Arifin Panigoro, Jusuf Kalla, Quraish Shihab, Sutrisno Bachir, Sutiyoso dll.
			</p>
		</div>
@endsection