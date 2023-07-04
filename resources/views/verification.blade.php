<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owletpay Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #8bc34a;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        a {
            display: block;
            padding: 10px;
            border: 1px solid #8bc34a;
            text-align: center
        }

        h2 {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/logo.png') }}" width="250px">
        <h4> Welcome to the OwletPay Bill Payment Platform! </h4>
        <p>Dear {{ $name }},</p>
        <p>
            I am excited to introduce you to our cutting-edge bill payment platform. Our mission is to revolutionize
            financial obligations by providing simplicity, speed, and security for managing bills. <br>

            With our platform, you can effortlessly take control of your bills, saving time and energy for the things
            that truly matter in life. OwletPay offers a unique and efficient way to manage your financial obligations,
            ensuring you can focus on the things that truly matter in life.
        </p>

        <p>
            1. Unmatched Convenience: Our platform simplifies bill-paying, allowing quick and convenient payments from
            home or on the go, covering all financial obligations, including utility and phone bills. <br>

            2. Security at the Core: Trust is crucial in handling personal and financial information, and our platform
            employs advanced encryption technologies and industry-leading security standards to ensure data protection.
            <br>

            3. Extensive Biller Network :Our biller network offers seamless bill payment across various sectors,
            including telecommunications, utilities, insurance, and subscriptions, without navigating multiple platforms
            or visiting individual websites.<br>

            4. Intuitive User Experience: Our platform is designed for simplicity, allowing even the most
            technologically challenged individuals to navigate with ease. Its user-friendly interface, intuitive
            features, and helpful tutorials simplify bill payments and save time and effort.<br>

            5. Prompt Customer Support: Our dedicated team of experts is available to assist with queries, concerns, and
            technical difficulties. Our top priority is customer satisfaction, and we go above and beyond to address
            your needs.<br>
        </p>
        <p>
            I want to personally thank you for choosing OwletPay as you go-to bill payment platform. We are delighted to
            have you on board, and we look forward to serving you diligently. With our platform, managing your bills
            will become a seamless and stress-free experience.
        </p>

        <p>
            Should you have any questions or require any assistance, please do not hesitate to reach out to our customer
            support team at support@owletpay.com. We are here to help. <br>

            Once again, welcome to OwletPay. Together, we will transform the way you handle your bills and empower you
            with newfound financial freedom.

        </p>

        <p>
            Best regards, <br><br>
            Olusehinde Elijah Kolawole
            CEO, THE-OWLET LTD
        </p>

    </div>
</body>

</html>
