<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>EWO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="signup_css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,700,600,300,800' rel='stylesheet' type='text/css'>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="signup_js/jquery.cookie.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script>
            $(document).ready(function ()
            {
                $('.btn').click(function ()
                {
                    if ($(this).attr('data-toggle') == 'hide')
                    {
                        $(this).attr('data-toggle', 'show');
                    } else
                    {
                        $(this).attr('data-toggle', 'hide');
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
        
                var checkthis = $.cookie('openDivFY');
                $('.exp').hide();
                //alert(checkthis);
                if (checkthis != null ) {
                    $('#'+checkthis.toLowerCase()).show();
                } else {
                    $('#sendmoney').show();
                }
      
                $(".explodeshow").click(function () {
            
                    //$.cookie('openDivFY', $(this).text().trim());
        
                    var idExplode = $(this).attr("class").split(" ");
                    var idExp = idExplode[1];
                    $('.exp').hide();
                    $("#"+idExp).show("fade", {}, 1000);
                });
        
                $("li").click(function(){
                    $('li').removeClass('selected');
                    $(this).addClass('selected');
                });
            });
        </script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <style>
            a {
                text-decoration: none;

            }
            .pointer {
                color: #9b59b6;
                font-family: 'Pacifico', cursive;
                font-size: 30px;
                margin-top: 15px;
            }
            pre {
                margin: 0px auto;
            }
            pre code {
                padding: 35px;
                border-radius: 5px;
                font-size: 15px;
                background: rgba(0,0,0,0.1);
                border: rgba(0,0,0,0.05) 5px solid;
                max-width: 500px;
            }
            .main {
                float: left;
                width: 100%;
                margin: 0 auto;
                background: #fff;
            }

            .main h1 {
                padding:20px 50px;
                float: left;
                width: 100%;
                font-size: 90px;
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                font-weight: 100;
                color: black;
                margin: 0;
                margin-top: 70px;
                font-family: 'Playfair Display';
                letter-spacing: -1px;
            }

            .main h1.demo1 {
                background: #1ABC9C;
            }

            .reload.bell {
                font-size: 12px;
                padding: 20px;
                width: 45px;
                text-align: center;
                height: 47px;
                border-radius: 50px;
                -webkit-border-radius: 50px;
                -moz-border-radius: 50px;
            }

            .reload.bell #notification {
                font-size: 25px;
                line-height: 140%;
            }


            .reload:hover{
                background: #A2261E;
            }

            .clear {
                width: auto;
            }
            .btn:hover, .btn:hover {
                background: rgba(0,0,0,0.8);
            }
            .btns {
                width: 410px;
                margin: 50px auto;
            }
            .credit {
                text-align: center;
                color: #444;
                padding: 10px;
                margin: 0 0 0 0;
                background: #161923;
                color: #FFF;
                float: left;
                width: 100%;
            }
            .credit a {
                color: #fff;
                text-decoration: none;
                font-weight: bold;
            }

            .back {
                position: absolute;
                top: 0;
                left: 0;
                text-align: center;
                display: block;
                padding: 7px;
                width: 100%;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
                background: rgba(255, 255, 255, 0.25);
                font-weight: bold;
                font-size: 13px;
                color: #000;
                -webkit-transition: all 200ms ease-out;
                -moz-transition: all 200ms ease-out;
                -o-transition: all 200ms ease-out;
                transition: all 200ms ease-out;
            }
            .back:hover {
                color: black;
                background: rgba(255, 255, 255, 0.5);
            }


            .page_container {
                max-width: 960px;
                margin: 0px auto;
            }

            .header {
                background: white;
                padding-top: 10px;
                margin-bottom: 0;
            }
            .header h1{
                margin-bottom: 0;
                font-size: 45px;
            }

            .header .menu {
                padding-bottom: 10px;
            }

            .benefits {
                color: black;
                height: 100px;
                background: #FFF;
                position: relative;
                width: 100%;
                padding: 25px;
                font-size: 40px;
                font-weight: 100;
                float: left;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
            }

            .benefits .page_container{
                margin-bottom: 50px;
                margin-top: 0;
            }

            .immersive_slider .is-slide .content h2{
                line-height: 140%;
                font-weight: 100;
                color: white;
                font-weight: 100;
            }
            .immersive_slider .is-slide .content a {
                color: white;
            }

            .immersive_slider .is-slide .content p{
                float: left;
                font-weight: 100;
                width: 100%;
                font-size: 17px;
                margin-top: 5px;
            }
        </style>
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Terms and Conditions</strong></h3>
                    <br clear="all"/></div>
            </div>
            <section id="ewo-features" class="ewo-row">
            </section>
        </div>
    </div>
    <br clear="all"/>
<section id="ewo-pricing" class="ewo-row">
    <div class="ewo-container">
        <h4 style="color:#565d67;">USING EASYWAY ORDERING </h4>
        <br/>
        <p>
            We own and operate this website (EasyWay Ordering.com), the related mobile sites and mobile application(s), and selected other domains (collectively, the "Sites"). These terms of use (the “Agreement”) constitute a contract between you and us. What does that mean? It means that by accessing and/or using the Sites, you agree to all the terms and conditions of this Agreement. If you do not agree to all the terms and conditions of this Agreement, do not use the Sites. As used in this Agreement, "EasyWay Ordering," "we," "us," and "our" shall mean EasyWay Ordering LLC and its subsidiaries and affiliates.
            You may only use the Sites if you can form a binding contract with us.</p>
        <br/>
        <p> You may only use the Sites to order if you are the authorized holder of the credit card used for payment or an authorized user of a corporate account. You are prohibited from using the Sites if you are under the age of 13.</p>
        <br/><br/>
        <h4 style="color:#565d67;">OUR MATERIALS AND LICENSE TO YOU</h4>
        <br/>
        <p>With the exception of User Content (detailed below), the Sites and everything on them, from text to photos to videos to graphics and software, (collectively, the "Materials") are owned by or licensed to EasyWay Ordering. The Sites and the Materials are protected by copyright, trademark, trade dress, domain name, patent, trade secret, international treaties and/or other proprietary rights and laws of the United States and other countries. Except as otherwise indicated on the Sites and except for the trademarks, service marks, logos and trade names of other companies that are or may be displayed on the Sites, all trademarks, service marks, logos, trade dress and trade names are proprietary to EasyWay Ordering. Please be advised that EasyWay Ordering actively and aggressively enforces its intellectual property rights to the fullest extent of the law.
        </p>
        <br/>
        <p>
            We grant you a limited, non-exclusive, non-transferable and revocable license to access and use the Sites and/or the Materials for your personal use, solely as expressly permitted by this Agreement and subject to all the terms and conditions of this Agreement, all applicable intellectual property laws, and any additional notices or restrictions contained on the Sites. Any other use of the Sites and/or the Materials is strictly prohibited. No Materials may be copied, republished, uploaded, posted, transmitted, distributed in any way, and/or modified without our express written permission. Nothing contained on the Sites should be interpreted as granting to you any license or right to use any of the Materials and/or third party proprietary content on the Sites without the express written permission of EasyWay Ordering or the appropriate third party owner, as applicable.
        </p><br/>
        <p>
            If you download any software from the Sites, you shall not redistribute, sell, decompile, reverse engineer, disassemble, or otherwise reduce the software to a human-perceivable form.
        </p>
        <br/><br/>
        <h4 style="color:#565d67;">YOUR CONTENT</h4>
        <br/>
        <p><strong>I. Content You Provide </strong></p>
        <br/>
        <p>EasyWay Ordering may provide you with interactive opportunities on the Sites, including, without limitation, features such as group orders, saved favorites, reputation management (when enrolled), social media links and interactive menus. You represent and warrant that you are the owner of and/or otherwise have the right to provide all information, comments, reviews, ratings and/or other materials and/or content that you submit, post and/or otherwise transmit to the Sites ("User Content").
        </p>

        <br/>
        <p><strong>II. Use of Your Content </strong></p>
        <br/>
        <p>
            You grant EasyWay Ordering an irrevocable, transferable, paid up, royalty-free, perpetual, non-exclusive worldwide sub-licensable license to use, copy, display, publish, modify, remove, publicly perform, translate, create derivative works from, distribute and/or otherwise use User Content on the Sites and in all forms of media now known or hereafter invented (collectively, the "Uses"), without notification to and/or approval by you. You further grant EasyWay Ordering a license to use your username and/or other user profile information, including without limitation, your ratings history and how long you have been a member of EasyWay Ordering, to attribute User Content to you in connection with the Uses, if we choose to do so, again without notification to and/or approval by you.
        </p>
        <br/>
        <p>
            User Content transmitted to certain parts of the Sites, including, without limitation, restaurant pages and certain Interactive Areas, may be posted in public areas on our Sites, including without limitation in a compilation format, and publicly visible and accessible. EasyWay Ordering and its officers, directors, employees, parents, subsidiaries, affiliates, successors, assigns, licensors, licensees, designees, business partners, contractors, agents and representatives (collectively, the “Released Parties”) will not be responsible for, and you hereby expressly release the Released Parties from any and all liability for, the action of any and all third parties with respect to User Content.
        </p><br/>
        <p>
            <strong>III. Standards of Conduct</strong> </p>

        <br/>
        <p>By transmitting User Content, you agree to follow the standards of conduct below, and any additional standards stated on the Sites.
        </p>
        <p>
            We do our best to encourage civility and discourage disruptive communication on the Sites. We also discourage communications that incite others to violate our standards. We expect your cooperation in upholding our standards. You are responsible for all User Content. You agree not to provide any User Content that:
        </p>
        <ul>
            <li>is unlawful, harmful to adults or minors, threatening, abusive, harassing, tortious, defamatory, vulgar, obscene, profane, offensive, invasive of another's privacy, hateful, and/or racially, ethnically and/or otherwise objectionable;
            </li>
            <li>
                has a commercial, political or religious purpose;
            </li>
            <li>
                is false, misleading and/or not written in good faith;
            </li>
            <li>
                infringes any patent, trademark, trade secret, copyright, right of privacy and/or publicity, and/or other proprietary rights of any person and/or entity;
            </li>
            <li>
                is illegal and/or promotes illegal activity;
            </li>
            <li>
                contains unauthorized advertising and/or solicits users to a business other than those on the Sites; and/or
            </li>
            <li>
                is intended to interrupt, destroy or limit the functionality or integrity of any computer software, hardware or Materials on the Sites or other websites.
            </li>
        </ul>
        <br/>
        <p>
            EasyWay Ordering may monitor any and all use of the Sites. We reserve the right to change, delete and/or remove, in part or in full, any User Content that we believe, and/or to terminate and/or suspend access to any Interactive Areas, any Materials and/or any Sites for conduct that we believe, violates our standards, violates any other terms and/or conditions of this Agreement, interferes with other peoples' enjoyment of the Materials and/or our Sites, and/or that we believe is inappropriate, in our sole discretion, and/or for any other reason, again in our sole discretion. EasyWay Ordering will cooperate with local, state and/or federal authorities to the extent permitted by applicable law in connection with User Content.
        </p>
        <br/><br/>

        <h4 style="color:#565d67;">TEXT ALERTS</h4>
        <br/>
        <p>
            You may sign up to receive text message ordering via your EasyWay Ordering platform, or through the EasyWay Ordering.com mobile application. Please note that standard data and message rates may apply for text message alerts. Please contact your mobile phone carrier for details.
        </p><br/><br/>

        <p><strong>Pricing</strong> </p>
        <br/>
        <p>
            Your mobile phone carrier’s standard message and data rates apply for any messages sent to you from us and from us to you. If you have any questions about your text or data plan, please contact your mobile phone carrier.
        </p>
        <br/>
        <p><strong>Supported Carriers </strong></p>
        <br/>
        <p>We are able to deliver text message alerts to the following mobile phone carriers:
        </p>

        <p><strong>Major carriers: </strong>AT&T, Verizon Wireless, Sprint, T-Mobile, U.S. Cellular, Alltel, Boost Mobile, and Virgin Mobile.
            Additional carriers: Alaska Communications Systems (ACS), Appalachian Wireless (EKN), Bluegrass Cellular, Cellular One of East Central IL (ECIT), Cellular One of Northeast Pennsylvania, Cincinnati Bell Wireless, Cricket, Coral Wireless (Mobil PCS), COX, Cross, Element Mobile (Flat Wireless), Epic Touch (Elkhart Telephone), GCI, Golden State, Hawkeye (Chat Mobility), Hawkeye (NW Missouri), Illinois Valley Cellular, Inland Cellular, iWireless (Iowa Wireless), Keystone Wireless (Immix Wireless/PC Man), MetroPCS, Mosaic (Consolidated or CTC Telecom), Nex-Tech Wireless, NTelos, Panhandle Communications, Pioneer, Plateau (Texas RSA 3 Ltd), Revol, RINA, Simmetry (TMP Corporation), Thumb Cellular, Union Wireless, United Wireless, Viaero Wireless, and West Central (WCC or 5 Star Wireless).
        </p>
        <br/><br/>
        <h4 style="color:#565d67;">INDEMNIFICATION</h4>
        <br/>
        <p>
            You agree to indemnify and hold harmless the Released Parties from all claims, actions, losses, judgments, liabilities, damages, costs and expenses (including reasonable attorneys' fees) arising out of your breach of any provision of this Agreement, your violation of applicable law, your use of the Sites and/or Materials (including without limitation all User Content), and/or all Uses by EasyWay Ordering and/or any third party authorized by EasyWay Ordering.
        </p><br/><br/>
        <h4 style="color:#565d67;">DISCLAIMER</h4><br/><br/>
        <p>THE SITES, THE MATERIALS AND ALL OTHER CONTENT ON THE SITES ARE PROVIDED "AS IS" AND WITHOUT WARRANTIES OF ANY KIND EITHER EXPRESS OR IMPLIED. TO THE FULLEST EXTENT PERMISSIBLE PURSUANT TO APPLICABLE LAW, THE RELEASED PARTIES DISCLAIM WITH RESPECT TO THE SITES, THE MATERIALS AND ALL OTHER CONTENT ON THE SITES ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. EASYWAY ORDERING DOES NOT REPRESENT OR WARRANT THAT THE SITES, THE MATERIALS AND/OR THE OTHER CONTENT ON THE SITES WILL BE SECURE, UNINTERRUPTED AND/OR ERROR-FREE, THAT DEFECTS WILL BE CORRECTED, AND/OR THAT THE SITES, THE MATERIALS AND/OR OTHER CONTENT ON THE SITES ARE FREE FROM VIRUSES OR OTHER HARMFUL COMPONENTS. EASYWAY ORDERING DOES NOT WARRANT OR MAKE ANY REPRESENTATIONS REGARDING THE USE OR THE RESULTS OF THE USE OF THE SITES, THE MATERIALS AND/OR ANY OTHER CONTENT ON THE SITES IN TERMS OF THEIR CORRECTNESS, ACCURACY, RELIABILITY, TIMELINESS, COMPLETENESS, CURRENTNESS, OR OTHERWISE, INCLUDING WITHOUT LIMITATION, THE QUALITY AND/OR TIMING OF A DELIVERY ORDERED ON THE SITES. YOU (AND NOT EASYWAY ORDERING) ASSUME THE ENTIRE COST OF ALL NECESSARY SERVICING, REPAIR, OR CORRECTION RELATING TO YOUR USE OF THE SITES, THE MATERIALS AND/OR OTHER CONTENT ON THE SITES. APPLICABLE LAW MAY NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSION MAY NOT FULLY APPLY TO YOU.
        </p>
        <br/><br/>


        <h4 style="color:#565d67;">LIMITATION OF LIABILITY</h4>
        <br/>
        <p>
            Under no circumstances, including, but not limited to, negligence, shall the Released Parties be liable to you and/or any third party (whether in contract, tort (including, but not limited to, negligence) and/or otherwise) for any special, consequential, incidental, direct, indirect, and/or punitive damages, lost profits, lost data, lost opportunities, costs of cover, and/or any other loss and/or injury that results from the use of, and/or the inability to use, the Sites, the Materials, User Content and/or any other content on the Sites (including without limitation relating to any products and/or services accessed through or otherwise in connection with the Sites), even if the Released Parties have been advised of the possibility of such damages, other loss and/or injury.
        </p>
        <br/><br/>

        <h4 style="color:#565d67;">THIRD PARTY LINKS</h4>

        <br/>
        <p>
            The Sites may contain links to websites that are owned, controlled, developed, sponsored and/or maintained by third parties and which may be subject to additional terms and conditions (“Third Party Websites”). EasyWay Ordering does not review, monitor, operate and/or control the Third Party Websites and EasyWay Ordering makes no guarantees, representations and/or warranties as to, and shall have no liability for, the content available on or through and/or the functioning of the Third Party Websites. By providing access to Third Party Websites, EasyWay Ordering is not recommending and/or otherwise endorsing the products and/or services provided by the sponsors and/or owners of those websites. You access and/or use the Third Party Websites, including providing information, materials and/or other content to the Third Party Websites, entirely at your own risk. EasyWay Ordering reserves the right to discontinue links to any Third Party Websites at any time and for any reason, without notice.
        </p>
        <br/><br/>

        <h4 style="color:#565d67;">PRIVACY POLICY</h4>

        <br/>
        <p>
            The terms and conditions of the EasyWay Ordering Privacy Policy located at http://www.EasyWay Ordering.com/privacy.html are incorporated into this Agreement by reference.

        </p>
        <br/><br/>

        <h4 style="color:#565d67;">COPYRIGHT POLICY</h4>

        <br/>

        <p>
            EasyWay Ordering respects the intellectual property of others, and we ask all of our users to do the same. If you believe that your copyrighted work has been copied and is accessible on the Sites or a website through which our services may be accessed in a way that constitutes copyright infringement, please provide EasyWay Ordering’ Copyright Agent (as set forth below) with notification containing the following information required by the Digital Millennium Copyright Act, 17 U.S.C. 512:
        </p>
        <br/>
        <p>1.	A physical or electronic signature of a person authorized to act on behalf of the copyright owner of the work that allegedly has been infringed;
        </p><p>
            2.	Identification of the copyrighted work claimed to have been infringed, or, if multiple copyrighted works allegedly have been infringed at a single online site, then a representative list of such copyrighted works;
        </p><p>
            3.	Identification of the material that is claimed to be infringing and that is to be removed or access to which is to be disabled, and information reasonably sufficient to permit us to locate the allegedly infringing material, e.g., the specific web page address on the Sites;
        </p><p>
            4.	Information reasonably sufficient to permit us to contact the party alleging infringement, including an email address;
        </p><p>
            5.	A statement that the party alleging infringement has a good-faith belief that use of the copyrighted work in the manner complained of is not authorized by the copyright owner, its agent, or the law; and
        </p><p>
            6.	A statement that the information in the notification is accurate, and under penalty of perjury, that the party alleging infringement is authorized to act on behalf of the copyright owner of the work that allegedly has been infringed.
        </p>
        <br/><br/>
        <p>Please send this notification to our copyright agent at: EasyWay Ordering, Attention: Copyright Agent, 50 Broad St. Suite 1701 New York, NY 10004.
        </p>
        <br/><br/>
        <h4 style="color:#565d67;">NO UNLAWFUL USE</h4>

        <br/>
        <p>
            By accessing and/or using the Sites, you agree that you will not use the Sites for any unlawful purpose or engage in any other use prohibited by this Agreement. You further agree that you will not use the Sites in any manner that could damage, disable, overburden and/or impair any EasyWay Ordering server, or the network(s) connected to any EasyWay Ordering server, and/or interfere with any other party’s use and enjoyment of the Sites. You may not attempt, through any means, to gain unauthorized access to any part of the Sites and/or any service, other account, computer system and/or network connected to any EasyWay Ordering server. You may not access the Sites using a third party’s account/registration. Unless EasyWay Ordering specifically authorizes you to do so in writing, you may not deep-link to the Sites and/or access the Sites manually and/or with any robot, spider, web crawler, extraction software, automated process and/or device to scrape, copy and/or monitor any portion of the Sites and/or any Materials and/or other content on the Sites. Further, you may not license, sell and/or otherwise provide access to and/or use of the Sites to any third party, including without limitation to build a competitive product and/or service.
        </p><br/><br/>

        <h4 style="color:#565d67;">VIOLATIONS OF THE AGREEMENT</h4>
        <br/>
        <p>
            EasyWay Ordering reserves the right to seek all remedies available at law and in equity for violations of the Agreement, including without limitation, the right to block access from a particular Internet and/or IP address to the Sites.
        </p><br/><br/>


        <h4 style="color:#565d67;">RESTAURANTS ONLY</h4>
        <br/>
        <p>
            Restaurants acknowledge and agree that EasyWay Ordering strives to provide timely communication to diners regarding the delivery of their orders. All restaurants have an obligation to confirm each order within 15 minutes of receipt. Repeated failure to confirm orders within the specified time frame will result in penalties, up to and including permanent closure, in our sole discretion.
        </p>
        <br/><br/>
        <h4 style="color:#565d67;">CHANGES TO THE AGREEMENT</h4>
        <br/>
        <p>
            We may change this Agreement from time to time and without prior notice. If we make a change to this Agreement it will be effective as soon as we post it, and the most current version of this Agreement will always be posted under the Terms of Use tab. If we make a material change to the Agreement, we may notify you. You agree that you will review this Agreement periodically. By continuing to access and/or use the Sites after we make changes to this Agreement, you agree to be bound by the revised Agreement. You agree that if you do not agree to the new terms of the Agreement, you will stop using the Sites.
        </p>
        <br/><br/>
        <h4 style="color:#565d67;">GOVERNING LAW</h4>
        <br/>
        <p>
            You acknowledge and agree that your access to and/or use of the Sites, the Materials and other content on the Sites is subject to all applicable international, federal, state and local laws and regulations. The terms, conditions and policies contained in this Agreement shall be governed by and construed in accordance with the laws of the State of New York, without regard to any principles of conflicts of law, and all claims, disputes or disagreements which may arise out of the interpretation, performance or in any way relating to your use of the Sites, the Materials and/or other content on the Sites shall be submitted exclusively to the jurisdiction of the state or federal courts of applicable jurisdiction located in the County and State of New York. You acknowledge and agree that you will irrevocably consent and submit to the exclusive personal jurisdiction of those courts for the purpose of litigating any such action; and you will irrevocably waive any jurisdictional, venue or inconvenient forum objections to such court. Any waiver by EasyWay Ordering of any provision of this Agreement must be in writing.
        </p>
        <br clear="all"/>
    </div>
    <br clear="all"/>
</div>          
</section>
</div>
<script>

    var _gaq=[['_setAccount','UA-11278966-1'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php include('footer.php'); ?>
</div>
</body>
</html>
