

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us </title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #fff;
      color: #333;
    }

    .about-section {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      padding: 60px 80px;
      background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%);
    }

    .about-image {
      flex: 1;
      text-align: center;
    }

    .about-image img {
      width: 90%;
      /* max-width: 450px; */
      border-radius:30px;
      height: 400px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: transform 0.4s ease;
    }

    .about-image img:hover {
      transform: scale(1.05);
    }

    .about-content {
      flex: 1;
      padding: 30px 40px;
      max-width: 600px;
    }

    .about-content h2 {
      font-size: 32px;
      color: #e91e63;
      margin-bottom: 20px;
      font-weight: 700;
      letter-spacing: 1px;
    }

    .about-content p {
      font-size: 16px;
      line-height: 1.8;
      margin-bottom: 15px;
    }

    .highlight {
      color: #e91e63;
      font-weight: 600;
    }

    @media (max-width: 900px) {
      .about-section {
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
      }

      .about-content {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <section class="about-section">
    <div class="about-image">
      <img src="assets/img/hero.webp" alt="PraShika Glam Salon">
    </div>

    <div class="about-content">
      <h2>About BeautyEase</h2>
      <p>
        Welcome to <span class="highlight">BeautyEase</span>,where beauty meets confidence, and self-care becomes an art. 
        We believe that every person deserves to feel radiant, inside and out. That's why we've created a warm, elegant space 
        where you can relax and rediscover your inner glow.
      </p>

      <p>
        Our journey began with a simple dream: to make beauty experiences accessible to everyone. 
        From stunning hairstyles and flawless makeup to soothing spa therapies, 
        we bring you services that blend <span class="highlight">luxury, comfort, and care</span>.
      </p>

      <p>
        At PraShika Glam, our expert stylists and therapists use premium products and modern techniques 
        to ensure every visit leaves you feeling pampered and confident. 
        Whether you’re preparing for a special occasion or treating yourself to a day of indulgence, 
        we’re here to make you shine your brightest.
      </p>

      <p>
        Come, unwind, and let <span class="highlight">BeautyEase</span> redefine your beauty experience
        because you deserve nothing less than perfection.
      </p>
    </div>
  </section>



</body>
</html>
