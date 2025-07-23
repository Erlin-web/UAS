<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Beasiswa Kampus - Raih Masa Depanmu!</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f9f9f9;
      color: #333;
    }

    header {
      background: linear-gradient(90deg, #4e54c8, #8f94fb);
      color: white;
      padding: 60px 20px;
      text-align: center;
    }

    header h1 {
      font-size: 3em;
      margin-bottom: 10px;
    }

    header p {
      font-size: 1.2em;
    }

    .cta-button {
      background: #ff7e5f;
      color: white;
      padding: 15px 30px;
      border: none;
      border-radius: 30px;
      font-size: 1em;
      cursor: pointer;
      margin-top: 20px;
      transition: background 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .cta-button:hover {
      background: #eb5e3e;
    }

    section {
      padding: 60px 20px;
      max-width: 1000px;
      margin: auto;
    }

    section h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 2em;
      color: #4e54c8;
    }

    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
    }

    .feature {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    .feature h3 {
      color: #ff7e5f;
      margin-bottom: 15px;
    }

    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Beasiswa Kampus</h1>
    <p>Raih masa depan gemilang dengan dukungan beasiswa terbaik untuk mahasiswa berprestasi.</p>
    <a href="{{ route('login') }}" class="cta-button">Daftar Sekarang</a>
  </header>

  <section id="about">
    <h2>Mengapa Beasiswa Kampus?</h2>
    <div class="features">
      <div class="feature">
        <h3>Beasiswa Prestasi</h3>
        <p>Dapatkan beasiswa untuk mahasiswa berprestasi akademik maupun non-akademik.</p>
      </div>
      <div class="feature">
        <h3>Dukungan Finansial</h3>
        <p>Membantu meringankan biaya kuliah hingga biaya hidup agar fokus belajar.</p>
      </div>
      <div class="feature">
        <h3>Proses Mudah</h3>
        <p>Proses pendaftaran online cepat, transparan, dan tanpa biaya.</p>
      </div>
    </div>
  </section>

  <footer>
    &copy; 2025 Beasiswa Kampus. All rights reserved.
  </footer>
</body>
</html>
