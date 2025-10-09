<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-country-list">
                                <a href="#endpoints-GETapi-country-list">GET api/country-list</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-country-details--id-">
                                <a href="#endpoints-GETapi-country-details--id-">GET api/country/details/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-business-type">
                                <a href="#endpoints-GETapi-business-type">GET api/business-type</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-check-device">
                                <a href="#endpoints-POSTapi-check-device">POST api/check-device</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-login">
                                <a href="#endpoints-POSTapi-user-login">POST api/user-login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-verify-otp">
                                <a href="#endpoints-POSTapi-verify-otp">POST api/verify-otp</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-set-mpin">
                                <a href="#endpoints-POSTapi-set-mpin">Step 4: Set MPIN</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-mpin-login">
                                <a href="#endpoints-POSTapi-mpin-login">Step 5: Login with MPIN and Device ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-forgot-mpin">
                                <a href="#endpoints-POSTapi-forgot-mpin">Step 1: Send OTP for Forgot MPIN</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-verify-otp-mpin">
                                <a href="#endpoints-POSTapi-verify-otp-mpin">Step 2: Verify OTP for Forgot MPIN</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-reset-mpin">
                                <a href="#endpoints-POSTapi-reset-mpin">Step 3: Reset MPIN After OTP Verification</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-logout">
                                <a href="#endpoints-POSTapi-logout">POST api/logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-profile">
                                <a href="#endpoints-GETapi-profile">GET api/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-dashboard">
                                <a href="#endpoints-GETapi-dashboard">GET api/dashboard</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-customer-list">
                                <a href="#endpoints-GETapi-customer-list">GET api/customer/list</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-customer-details--id-">
                                <a href="#endpoints-GETapi-customer-details--id-">GET api/customer/details/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-customer-filter">
                                <a href="#endpoints-GETapi-customer-filter">GET api/customer/filter</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-customer-store">
                                <a href="#endpoints-POSTapi-customer-store">POST api/customer/store</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-customer-update--id-">
                                <a href="#endpoints-POSTapi-customer-update--id-">POST api/customer/update/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-customer-order-list">
                                <a href="#endpoints-GETapi-customer-order-list">GET api/customer/order/list</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-store">
                                <a href="#endpoints-POSTapi-user-store">POST api/user/store</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user-list">
                                <a href="#endpoints-GETapi-user-list">GET api/user/list</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user-search">
                                <a href="#endpoints-GETapi-user-search">GET api/user/search</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user-show--id-">
                                <a href="#endpoints-GETapi-user-show--id-">GET api/user/show/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-category">
                                <a href="#endpoints-GETapi-category">GET api/category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-category-category-collection-wise--categoryid-">
                                <a href="#endpoints-GETapi-category-category-collection-wise--categoryid-">GET api/category/category-collection-wise/{categoryid}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-collection">
                                <a href="#endpoints-GETapi-collection">GET api/collection</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-product-products-category-collection-wise">
                                <a href="#endpoints-GETapi-product-products-category-collection-wise">GET api/product/products-category-collection-wise</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-product-products-collection-wise">
                                <a href="#endpoints-GETapi-product-products-collection-wise">GET api/product/products-collection-wise</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-product-fabric--id-">
                                <a href="#endpoints-GETapi-product-fabric--id-">GET api/product/fabric/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-product-check-price">
                                <a href="#endpoints-GETapi-product-check-price">GET api/product/check/price</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-product-measurement-product-wise">
                                <a href="#endpoints-GETapi-product-measurement-product-wise">GET api/product/measurement-product-wise</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-catalogue-list">
                                <a href="#endpoints-GETapi-catalogue-list">GET api/catalogue/list</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-catalogue-pages">
                                <a href="#endpoints-GETapi-catalogue-pages">GET api/catalogue/pages</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-catalogue-page-item">
                                <a href="#endpoints-GETapi-catalogue-page-item">GET api/catalogue/page/item</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-catalogue-products-collection-wise">
                                <a href="#endpoints-GETapi-catalogue-products-collection-wise">GET api/catalogue/products-collection-wise</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-order-video-store">
                                <a href="#endpoints-POSTapi-order-video-store">POST api/order/video/store</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-order-list">
                                <a href="#endpoints-GETapi-order-list">GET api/order/list</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-order-detail">
                                <a href="#endpoints-GETapi-order-detail">GET api/order/detail</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-order-ledger-view">
                                <a href="#endpoints-GETapi-order-ledger-view">GET api/order/ledger-view</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-order-payment-receipt-save">
                                <a href="#endpoints-POSTapi-order-payment-receipt-save">POST api/order/payment-receipt-save</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: October 9, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-country-list">GET api/country-list</h2>

<p>
</p>



<span id="example-requests-GETapi-country-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/country-list" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/country-list"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-country-list">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;message&quot;: &quot;Country list retrieved successfully&quot;,
    &quot;countries&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;Afghanistan&quot;,
            &quot;country_code&quot;: &quot;+93&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 2,
            &quot;title&quot;: &quot;Albania&quot;,
            &quot;country_code&quot;: &quot;+355&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 3,
            &quot;title&quot;: &quot;Algeria&quot;,
            &quot;country_code&quot;: &quot;+213&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 4,
            &quot;title&quot;: &quot;Andorra&quot;,
            &quot;country_code&quot;: &quot;+376&quot;,
            &quot;mobile_length&quot;: 6
        },
        {
            &quot;id&quot;: 5,
            &quot;title&quot;: &quot;Angola&quot;,
            &quot;country_code&quot;: &quot;+244&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 6,
            &quot;title&quot;: &quot;Antigua and Barbuda&quot;,
            &quot;country_code&quot;: &quot;+1-268&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 7,
            &quot;title&quot;: &quot;Argentina&quot;,
            &quot;country_code&quot;: &quot;+54&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 8,
            &quot;title&quot;: &quot;Armenia&quot;,
            &quot;country_code&quot;: &quot;+374&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 9,
            &quot;title&quot;: &quot;Australia&quot;,
            &quot;country_code&quot;: &quot;+61&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 10,
            &quot;title&quot;: &quot;Austria&quot;,
            &quot;country_code&quot;: &quot;+43&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 11,
            &quot;title&quot;: &quot;Azerbaijan&quot;,
            &quot;country_code&quot;: &quot;+994&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 12,
            &quot;title&quot;: &quot;Bahamas&quot;,
            &quot;country_code&quot;: &quot;+1-242&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 13,
            &quot;title&quot;: &quot;Bahrain&quot;,
            &quot;country_code&quot;: &quot;+973&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 14,
            &quot;title&quot;: &quot;Bangladesh&quot;,
            &quot;country_code&quot;: &quot;+880&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 15,
            &quot;title&quot;: &quot;Barbados&quot;,
            &quot;country_code&quot;: &quot;+1-246&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 16,
            &quot;title&quot;: &quot;Belarus&quot;,
            &quot;country_code&quot;: &quot;+375&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 17,
            &quot;title&quot;: &quot;Belgium&quot;,
            &quot;country_code&quot;: &quot;+32&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 18,
            &quot;title&quot;: &quot;Belize&quot;,
            &quot;country_code&quot;: &quot;+501&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 19,
            &quot;title&quot;: &quot;Benin&quot;,
            &quot;country_code&quot;: &quot;+229&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 20,
            &quot;title&quot;: &quot;Bhutan&quot;,
            &quot;country_code&quot;: &quot;+975&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 21,
            &quot;title&quot;: &quot;Bolivia&quot;,
            &quot;country_code&quot;: &quot;+591&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 22,
            &quot;title&quot;: &quot;Bosnia and Herzegovina&quot;,
            &quot;country_code&quot;: &quot;+387&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 23,
            &quot;title&quot;: &quot;Botswana&quot;,
            &quot;country_code&quot;: &quot;+267&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 24,
            &quot;title&quot;: &quot;Brazil&quot;,
            &quot;country_code&quot;: &quot;+55&quot;,
            &quot;mobile_length&quot;: 11
        },
        {
            &quot;id&quot;: 25,
            &quot;title&quot;: &quot;Brunei&quot;,
            &quot;country_code&quot;: &quot;+673&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 26,
            &quot;title&quot;: &quot;Bulgaria&quot;,
            &quot;country_code&quot;: &quot;+359&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 27,
            &quot;title&quot;: &quot;Burkina Faso&quot;,
            &quot;country_code&quot;: &quot;+226&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 28,
            &quot;title&quot;: &quot;Burundi&quot;,
            &quot;country_code&quot;: &quot;+257&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 29,
            &quot;title&quot;: &quot;Cambodia&quot;,
            &quot;country_code&quot;: &quot;+855&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 30,
            &quot;title&quot;: &quot;Cameroon&quot;,
            &quot;country_code&quot;: &quot;+237&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 31,
            &quot;title&quot;: &quot;Canada&quot;,
            &quot;country_code&quot;: &quot;+1&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 32,
            &quot;title&quot;: &quot;Cape Verde&quot;,
            &quot;country_code&quot;: &quot;+238&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 33,
            &quot;title&quot;: &quot;Central African Republic&quot;,
            &quot;country_code&quot;: &quot;+236&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 34,
            &quot;title&quot;: &quot;Chad&quot;,
            &quot;country_code&quot;: &quot;+235&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 35,
            &quot;title&quot;: &quot;Chile&quot;,
            &quot;country_code&quot;: &quot;+56&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 36,
            &quot;title&quot;: &quot;China&quot;,
            &quot;country_code&quot;: &quot;+86&quot;,
            &quot;mobile_length&quot;: 11
        },
        {
            &quot;id&quot;: 37,
            &quot;title&quot;: &quot;Colombia&quot;,
            &quot;country_code&quot;: &quot;+57&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 38,
            &quot;title&quot;: &quot;Comoros&quot;,
            &quot;country_code&quot;: &quot;+269&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 39,
            &quot;title&quot;: &quot;Congo (DRC)&quot;,
            &quot;country_code&quot;: &quot;+243&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 40,
            &quot;title&quot;: &quot;Congo (Republic)&quot;,
            &quot;country_code&quot;: &quot;+242&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 41,
            &quot;title&quot;: &quot;Costa Rica&quot;,
            &quot;country_code&quot;: &quot;+506&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 42,
            &quot;title&quot;: &quot;Croatia&quot;,
            &quot;country_code&quot;: &quot;+385&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 43,
            &quot;title&quot;: &quot;Cuba&quot;,
            &quot;country_code&quot;: &quot;+53&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 44,
            &quot;title&quot;: &quot;Cyprus&quot;,
            &quot;country_code&quot;: &quot;+357&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 45,
            &quot;title&quot;: &quot;Czech Republic&quot;,
            &quot;country_code&quot;: &quot;+420&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 46,
            &quot;title&quot;: &quot;Denmark&quot;,
            &quot;country_code&quot;: &quot;+45&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 47,
            &quot;title&quot;: &quot;Djibouti&quot;,
            &quot;country_code&quot;: &quot;+253&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 48,
            &quot;title&quot;: &quot;Dominica&quot;,
            &quot;country_code&quot;: &quot;+1-767&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 49,
            &quot;title&quot;: &quot;Dominican Republic&quot;,
            &quot;country_code&quot;: &quot;+1-809&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 50,
            &quot;title&quot;: &quot;Ecuador&quot;,
            &quot;country_code&quot;: &quot;+593&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 51,
            &quot;title&quot;: &quot;Egypt&quot;,
            &quot;country_code&quot;: &quot;+20&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 52,
            &quot;title&quot;: &quot;El Salvador&quot;,
            &quot;country_code&quot;: &quot;+503&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 53,
            &quot;title&quot;: &quot;Equatorial Guinea&quot;,
            &quot;country_code&quot;: &quot;+240&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 54,
            &quot;title&quot;: &quot;Eritrea&quot;,
            &quot;country_code&quot;: &quot;+291&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 55,
            &quot;title&quot;: &quot;Estonia&quot;,
            &quot;country_code&quot;: &quot;+372&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 56,
            &quot;title&quot;: &quot;Eswatini (Swaziland)&quot;,
            &quot;country_code&quot;: &quot;+268&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 57,
            &quot;title&quot;: &quot;Ethiopia&quot;,
            &quot;country_code&quot;: &quot;+251&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 58,
            &quot;title&quot;: &quot;Fiji&quot;,
            &quot;country_code&quot;: &quot;+679&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 59,
            &quot;title&quot;: &quot;Finland&quot;,
            &quot;country_code&quot;: &quot;+358&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 60,
            &quot;title&quot;: &quot;France&quot;,
            &quot;country_code&quot;: &quot;+33&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 61,
            &quot;title&quot;: &quot;Gabon&quot;,
            &quot;country_code&quot;: &quot;+241&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 62,
            &quot;title&quot;: &quot;Gambia&quot;,
            &quot;country_code&quot;: &quot;+220&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 63,
            &quot;title&quot;: &quot;Georgia&quot;,
            &quot;country_code&quot;: &quot;+995&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 64,
            &quot;title&quot;: &quot;Germany&quot;,
            &quot;country_code&quot;: &quot;+49&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 65,
            &quot;title&quot;: &quot;Ghana&quot;,
            &quot;country_code&quot;: &quot;+233&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 66,
            &quot;title&quot;: &quot;Greece&quot;,
            &quot;country_code&quot;: &quot;+30&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 67,
            &quot;title&quot;: &quot;Grenada&quot;,
            &quot;country_code&quot;: &quot;+1-473&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 68,
            &quot;title&quot;: &quot;Guatemala&quot;,
            &quot;country_code&quot;: &quot;+502&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 69,
            &quot;title&quot;: &quot;Guinea&quot;,
            &quot;country_code&quot;: &quot;+224&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 70,
            &quot;title&quot;: &quot;Guinea-Bissau&quot;,
            &quot;country_code&quot;: &quot;+245&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 71,
            &quot;title&quot;: &quot;Guyana&quot;,
            &quot;country_code&quot;: &quot;+592&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 72,
            &quot;title&quot;: &quot;Haiti&quot;,
            &quot;country_code&quot;: &quot;+509&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 73,
            &quot;title&quot;: &quot;Honduras&quot;,
            &quot;country_code&quot;: &quot;+504&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 74,
            &quot;title&quot;: &quot;Hungary&quot;,
            &quot;country_code&quot;: &quot;+36&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 75,
            &quot;title&quot;: &quot;Iceland&quot;,
            &quot;country_code&quot;: &quot;+354&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 76,
            &quot;title&quot;: &quot;India&quot;,
            &quot;country_code&quot;: &quot;+91&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 77,
            &quot;title&quot;: &quot;Indonesia&quot;,
            &quot;country_code&quot;: &quot;+62&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 78,
            &quot;title&quot;: &quot;Iran&quot;,
            &quot;country_code&quot;: &quot;+98&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 79,
            &quot;title&quot;: &quot;Iraq&quot;,
            &quot;country_code&quot;: &quot;+964&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 80,
            &quot;title&quot;: &quot;Ireland&quot;,
            &quot;country_code&quot;: &quot;+353&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 81,
            &quot;title&quot;: &quot;Israel&quot;,
            &quot;country_code&quot;: &quot;+972&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 82,
            &quot;title&quot;: &quot;Italy&quot;,
            &quot;country_code&quot;: &quot;+39&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 83,
            &quot;title&quot;: &quot;Jamaica&quot;,
            &quot;country_code&quot;: &quot;+1-876&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 84,
            &quot;title&quot;: &quot;Japan&quot;,
            &quot;country_code&quot;: &quot;+81&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 85,
            &quot;title&quot;: &quot;Jordan&quot;,
            &quot;country_code&quot;: &quot;+962&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 86,
            &quot;title&quot;: &quot;Kazakhstan&quot;,
            &quot;country_code&quot;: &quot;+7&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 87,
            &quot;title&quot;: &quot;Kenya&quot;,
            &quot;country_code&quot;: &quot;+254&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 88,
            &quot;title&quot;: &quot;Kuwait&quot;,
            &quot;country_code&quot;: &quot;+965&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 89,
            &quot;title&quot;: &quot;Kyrgyzstan&quot;,
            &quot;country_code&quot;: &quot;+996&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 90,
            &quot;title&quot;: &quot;Laos&quot;,
            &quot;country_code&quot;: &quot;+856&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 91,
            &quot;title&quot;: &quot;Latvia&quot;,
            &quot;country_code&quot;: &quot;+371&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 92,
            &quot;title&quot;: &quot;Lebanon&quot;,
            &quot;country_code&quot;: &quot;+961&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 93,
            &quot;title&quot;: &quot;Lesotho&quot;,
            &quot;country_code&quot;: &quot;+266&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 94,
            &quot;title&quot;: &quot;Liberia&quot;,
            &quot;country_code&quot;: &quot;+231&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 95,
            &quot;title&quot;: &quot;Libya&quot;,
            &quot;country_code&quot;: &quot;+218&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 96,
            &quot;title&quot;: &quot;Lithuania&quot;,
            &quot;country_code&quot;: &quot;+370&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 97,
            &quot;title&quot;: &quot;Luxembourg&quot;,
            &quot;country_code&quot;: &quot;+352&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 98,
            &quot;title&quot;: &quot;Madagascar&quot;,
            &quot;country_code&quot;: &quot;+261&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 99,
            &quot;title&quot;: &quot;Malawi&quot;,
            &quot;country_code&quot;: &quot;+265&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 100,
            &quot;title&quot;: &quot;Malaysia&quot;,
            &quot;country_code&quot;: &quot;+60&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 101,
            &quot;title&quot;: &quot;Maldives&quot;,
            &quot;country_code&quot;: &quot;+960&quot;,
            &quot;mobile_length&quot;: 7
        },
        {
            &quot;id&quot;: 102,
            &quot;title&quot;: &quot;Mali&quot;,
            &quot;country_code&quot;: &quot;+223&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 103,
            &quot;title&quot;: &quot;Malta&quot;,
            &quot;country_code&quot;: &quot;+356&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 104,
            &quot;title&quot;: &quot;Mauritania&quot;,
            &quot;country_code&quot;: &quot;+222&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 105,
            &quot;title&quot;: &quot;Mauritius&quot;,
            &quot;country_code&quot;: &quot;+230&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 106,
            &quot;title&quot;: &quot;Mexico&quot;,
            &quot;country_code&quot;: &quot;+52&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 107,
            &quot;title&quot;: &quot;Moldova&quot;,
            &quot;country_code&quot;: &quot;+373&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 108,
            &quot;title&quot;: &quot;Monaco&quot;,
            &quot;country_code&quot;: &quot;+377&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 109,
            &quot;title&quot;: &quot;Mongolia&quot;,
            &quot;country_code&quot;: &quot;+976&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 110,
            &quot;title&quot;: &quot;Montenegro&quot;,
            &quot;country_code&quot;: &quot;+382&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 111,
            &quot;title&quot;: &quot;Morocco&quot;,
            &quot;country_code&quot;: &quot;+212&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 112,
            &quot;title&quot;: &quot;Mozambique&quot;,
            &quot;country_code&quot;: &quot;+258&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 113,
            &quot;title&quot;: &quot;Myanmar&quot;,
            &quot;country_code&quot;: &quot;+95&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 114,
            &quot;title&quot;: &quot;Namibia&quot;,
            &quot;country_code&quot;: &quot;+264&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 115,
            &quot;title&quot;: &quot;Nepal&quot;,
            &quot;country_code&quot;: &quot;+977&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 116,
            &quot;title&quot;: &quot;Netherlands&quot;,
            &quot;country_code&quot;: &quot;+31&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 117,
            &quot;title&quot;: &quot;New Zealand&quot;,
            &quot;country_code&quot;: &quot;+64&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 118,
            &quot;title&quot;: &quot;Nicaragua&quot;,
            &quot;country_code&quot;: &quot;+505&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 119,
            &quot;title&quot;: &quot;Niger&quot;,
            &quot;country_code&quot;: &quot;+227&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 120,
            &quot;title&quot;: &quot;Nigeria&quot;,
            &quot;country_code&quot;: &quot;+234&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 121,
            &quot;title&quot;: &quot;North Korea&quot;,
            &quot;country_code&quot;: &quot;+850&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 122,
            &quot;title&quot;: &quot;Norway&quot;,
            &quot;country_code&quot;: &quot;+47&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 123,
            &quot;title&quot;: &quot;Oman&quot;,
            &quot;country_code&quot;: &quot;+968&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 124,
            &quot;title&quot;: &quot;Pakistan&quot;,
            &quot;country_code&quot;: &quot;+92&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 125,
            &quot;title&quot;: &quot;Panama&quot;,
            &quot;country_code&quot;: &quot;+507&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 126,
            &quot;title&quot;: &quot;Paraguay&quot;,
            &quot;country_code&quot;: &quot;+595&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 127,
            &quot;title&quot;: &quot;Peru&quot;,
            &quot;country_code&quot;: &quot;+51&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 128,
            &quot;title&quot;: &quot;Philippines&quot;,
            &quot;country_code&quot;: &quot;+63&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 129,
            &quot;title&quot;: &quot;Poland&quot;,
            &quot;country_code&quot;: &quot;+48&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 130,
            &quot;title&quot;: &quot;Portugal&quot;,
            &quot;country_code&quot;: &quot;+351&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 131,
            &quot;title&quot;: &quot;Qatar&quot;,
            &quot;country_code&quot;: &quot;+974&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 132,
            &quot;title&quot;: &quot;Romania&quot;,
            &quot;country_code&quot;: &quot;+40&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 133,
            &quot;title&quot;: &quot;Russia&quot;,
            &quot;country_code&quot;: &quot;+7&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 134,
            &quot;title&quot;: &quot;Rwanda&quot;,
            &quot;country_code&quot;: &quot;+250&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 135,
            &quot;title&quot;: &quot;Saudi Arabia&quot;,
            &quot;country_code&quot;: &quot;+966&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 136,
            &quot;title&quot;: &quot;Senegal&quot;,
            &quot;country_code&quot;: &quot;+221&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 137,
            &quot;title&quot;: &quot;Serbia&quot;,
            &quot;country_code&quot;: &quot;+381&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 138,
            &quot;title&quot;: &quot;Singapore&quot;,
            &quot;country_code&quot;: &quot;+65&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 139,
            &quot;title&quot;: &quot;South Africa&quot;,
            &quot;country_code&quot;: &quot;+27&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 140,
            &quot;title&quot;: &quot;South Korea&quot;,
            &quot;country_code&quot;: &quot;+82&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 141,
            &quot;title&quot;: &quot;Spain&quot;,
            &quot;country_code&quot;: &quot;+34&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 142,
            &quot;title&quot;: &quot;Sri Lanka&quot;,
            &quot;country_code&quot;: &quot;+94&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 143,
            &quot;title&quot;: &quot;Sudan&quot;,
            &quot;country_code&quot;: &quot;+249&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 144,
            &quot;title&quot;: &quot;Sweden&quot;,
            &quot;country_code&quot;: &quot;+46&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 145,
            &quot;title&quot;: &quot;Switzerland&quot;,
            &quot;country_code&quot;: &quot;+41&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 146,
            &quot;title&quot;: &quot;Syria&quot;,
            &quot;country_code&quot;: &quot;+963&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 147,
            &quot;title&quot;: &quot;Taiwan&quot;,
            &quot;country_code&quot;: &quot;+886&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 148,
            &quot;title&quot;: &quot;Tanzania&quot;,
            &quot;country_code&quot;: &quot;+255&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 149,
            &quot;title&quot;: &quot;Thailand&quot;,
            &quot;country_code&quot;: &quot;+66&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 150,
            &quot;title&quot;: &quot;Tunisia&quot;,
            &quot;country_code&quot;: &quot;+216&quot;,
            &quot;mobile_length&quot;: 8
        },
        {
            &quot;id&quot;: 151,
            &quot;title&quot;: &quot;Turkey&quot;,
            &quot;country_code&quot;: &quot;+90&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 152,
            &quot;title&quot;: &quot;Uganda&quot;,
            &quot;country_code&quot;: &quot;+256&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 153,
            &quot;title&quot;: &quot;Ukraine&quot;,
            &quot;country_code&quot;: &quot;+380&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 154,
            &quot;title&quot;: &quot;United Arab Emirates&quot;,
            &quot;country_code&quot;: &quot;+971&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 155,
            &quot;title&quot;: &quot;United Kingdom&quot;,
            &quot;country_code&quot;: &quot;+44&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 156,
            &quot;title&quot;: &quot;United States&quot;,
            &quot;country_code&quot;: &quot;+1&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 157,
            &quot;title&quot;: &quot;Uruguay&quot;,
            &quot;country_code&quot;: &quot;+598&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 158,
            &quot;title&quot;: &quot;Uzbekistan&quot;,
            &quot;country_code&quot;: &quot;+998&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 159,
            &quot;title&quot;: &quot;Venezuela&quot;,
            &quot;country_code&quot;: &quot;+58&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 160,
            &quot;title&quot;: &quot;Vietnam&quot;,
            &quot;country_code&quot;: &quot;+84&quot;,
            &quot;mobile_length&quot;: 10
        },
        {
            &quot;id&quot;: 161,
            &quot;title&quot;: &quot;Zambia&quot;,
            &quot;country_code&quot;: &quot;+260&quot;,
            &quot;mobile_length&quot;: 9
        },
        {
            &quot;id&quot;: 162,
            &quot;title&quot;: &quot;Zimbabwe&quot;,
            &quot;country_code&quot;: &quot;+263&quot;,
            &quot;mobile_length&quot;: 9
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-country-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-country-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-country-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-country-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-country-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-country-list" data-method="GET"
      data-path="api/country-list"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-country-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-country-list"
                    onclick="tryItOut('GETapi-country-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-country-list"
                    onclick="cancelTryOut('GETapi-country-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-country-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/country-list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-country-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-country-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-country-details--id-">GET api/country/details/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-country-details--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/country/details/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/country/details/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-country-details--id-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Country not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-country-details--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-country-details--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-country-details--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-country-details--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-country-details--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-country-details--id-" data-method="GET"
      data-path="api/country/details/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-country-details--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-country-details--id-"
                    onclick="tryItOut('GETapi-country-details--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-country-details--id-"
                    onclick="cancelTryOut('GETapi-country-details--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-country-details--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/country/details/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-country-details--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-country-details--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-country-details--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the detail. Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-business-type">GET api/business-type</h2>

<p>
</p>



<span id="example-requests-GETapi-business-type">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/business-type" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/business-type"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-business-type">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 57
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;message&quot;: &quot;Business Type list fetched successfully!&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;TEXTILES&quot;,
            &quot;image&quot;: null,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-business-type" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-business-type"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-business-type"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-business-type" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-business-type">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-business-type" data-method="GET"
      data-path="api/business-type"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-business-type', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-business-type"
                    onclick="tryItOut('GETapi-business-type');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-business-type"
                    onclick="cancelTryOut('GETapi-business-type');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-business-type"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/business-type</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-business-type"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-business-type"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-check-device">POST api/check-device</h2>

<p>
</p>



<span id="example-requests-POSTapi-check-device">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/check-device" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/check-device"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-check-device">
</span>
<span id="execution-results-POSTapi-check-device" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-check-device"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-check-device"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-check-device" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-check-device">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-check-device" data-method="POST"
      data-path="api/check-device"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-check-device', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-check-device"
                    onclick="tryItOut('POSTapi-check-device');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-check-device"
                    onclick="cancelTryOut('POSTapi-check-device');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-check-device"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/check-device</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-check-device"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-check-device"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-check-device"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-user-login">POST api/user-login</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/user-login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"country_code\": \"consequatur\",
    \"mobile\": 11613.31890586,
    \"device_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user-login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "country_code": "consequatur",
    "mobile": 11613.31890586,
    "device_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-login">
</span>
<span id="execution-results-POSTapi-user-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-user-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-user-login" data-method="POST"
      data-path="api/user-login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-login"
                    onclick="tryItOut('POSTapi-user-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-login"
                    onclick="cancelTryOut('POSTapi-user-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user-login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-user-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-user-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country_code"                data-endpoint="POSTapi-user-login"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mobile"                data-endpoint="POSTapi-user-login"
               value="11613.31890586"
               data-component="body">
    <br>
<p>Example: <code>11613.31890586</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-user-login"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-verify-otp">POST api/verify-otp</h2>

<p>
</p>



<span id="example-requests-POSTapi-verify-otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/verify-otp" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"country_code\": \"consequatur\",
    \"mobile\": \"consequatur\",
    \"otp\": \"8107\",
    \"device_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/verify-otp"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "country_code": "consequatur",
    "mobile": "consequatur",
    "otp": "8107",
    "device_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-verify-otp">
</span>
<span id="execution-results-POSTapi-verify-otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-verify-otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-verify-otp"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-verify-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-verify-otp">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-verify-otp" data-method="POST"
      data-path="api/verify-otp"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-verify-otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-verify-otp"
                    onclick="tryItOut('POSTapi-verify-otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-verify-otp"
                    onclick="cancelTryOut('POSTapi-verify-otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-verify-otp"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/verify-otp</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country_code"                data-endpoint="POSTapi-verify-otp"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-verify-otp"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>phone</code> of an existing record in the users table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-verify-otp"
               value="8107"
               data-component="body">
    <br>
<p>Must be 4 digits. Example: <code>8107</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-verify-otp"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-set-mpin">Step 4: Set MPIN</h2>

<p>
</p>



<span id="example-requests-POSTapi-set-mpin">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/set-mpin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"consequatur\",
    \"mpin\": \"8107\",
    \"device_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/set-mpin"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "consequatur",
    "mpin": "8107",
    "device_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-set-mpin">
</span>
<span id="execution-results-POSTapi-set-mpin" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-set-mpin"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-set-mpin"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-set-mpin" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-set-mpin">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-set-mpin" data-method="POST"
      data-path="api/set-mpin"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-set-mpin', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-set-mpin"
                    onclick="tryItOut('POSTapi-set-mpin');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-set-mpin"
                    onclick="cancelTryOut('POSTapi-set-mpin');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-set-mpin"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/set-mpin</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-set-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-set-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-set-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>mobile</code> of an existing record in the user_logins table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mpin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mpin"                data-endpoint="POSTapi-set-mpin"
               value="8107"
               data-component="body">
    <br>
<p>Must be 4 digits. Example: <code>8107</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-set-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-mpin-login">Step 5: Login with MPIN and Device ID</h2>

<p>
</p>



<span id="example-requests-POSTapi-mpin-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/mpin-login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"consequatur\",
    \"mpin\": \"8107\",
    \"device_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/mpin-login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "consequatur",
    "mpin": "8107",
    "device_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-mpin-login">
</span>
<span id="execution-results-POSTapi-mpin-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-mpin-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-mpin-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-mpin-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-mpin-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-mpin-login" data-method="POST"
      data-path="api/mpin-login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-mpin-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-mpin-login"
                    onclick="tryItOut('POSTapi-mpin-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-mpin-login"
                    onclick="cancelTryOut('POSTapi-mpin-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-mpin-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/mpin-login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-mpin-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-mpin-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-mpin-login"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>mobile</code> of an existing record in the user_logins table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mpin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mpin"                data-endpoint="POSTapi-mpin-login"
               value="8107"
               data-component="body">
    <br>
<p>Must be 4 digits. Example: <code>8107</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-mpin-login"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-forgot-mpin">Step 1: Send OTP for Forgot MPIN</h2>

<p>
</p>



<span id="example-requests-POSTapi-forgot-mpin">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/forgot-mpin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"country_code\": \"consequatur\",
    \"mobile\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/forgot-mpin"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "country_code": "consequatur",
    "mobile": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-forgot-mpin">
</span>
<span id="execution-results-POSTapi-forgot-mpin" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-forgot-mpin"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-forgot-mpin"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-forgot-mpin" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-forgot-mpin">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-forgot-mpin" data-method="POST"
      data-path="api/forgot-mpin"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-forgot-mpin', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-forgot-mpin"
                    onclick="tryItOut('POSTapi-forgot-mpin');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-forgot-mpin"
                    onclick="cancelTryOut('POSTapi-forgot-mpin');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-forgot-mpin"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/forgot-mpin</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-forgot-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-forgot-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country_code"                data-endpoint="POSTapi-forgot-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-forgot-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>mobile</code> of an existing record in the user_logins table. Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-verify-otp-mpin">Step 2: Verify OTP for Forgot MPIN</h2>

<p>
</p>



<span id="example-requests-POSTapi-verify-otp-mpin">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/verify-otp-mpin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"country_code\": \"consequatur\",
    \"mobile\": \"consequatur\",
    \"otp\": \"8107\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/verify-otp-mpin"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "country_code": "consequatur",
    "mobile": "consequatur",
    "otp": "8107"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-verify-otp-mpin">
</span>
<span id="execution-results-POSTapi-verify-otp-mpin" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-verify-otp-mpin"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-verify-otp-mpin"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-verify-otp-mpin" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-verify-otp-mpin">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-verify-otp-mpin" data-method="POST"
      data-path="api/verify-otp-mpin"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-verify-otp-mpin', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-verify-otp-mpin"
                    onclick="tryItOut('POSTapi-verify-otp-mpin');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-verify-otp-mpin"
                    onclick="cancelTryOut('POSTapi-verify-otp-mpin');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-verify-otp-mpin"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/verify-otp-mpin</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-verify-otp-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-verify-otp-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country_code"                data-endpoint="POSTapi-verify-otp-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-verify-otp-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>mobile</code> of an existing record in the user_logins table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-verify-otp-mpin"
               value="8107"
               data-component="body">
    <br>
<p>Must be 4 digits. Example: <code>8107</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-reset-mpin">Step 3: Reset MPIN After OTP Verification</h2>

<p>
</p>



<span id="example-requests-POSTapi-reset-mpin">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/reset-mpin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"country_code\": \"consequatur\",
    \"mobile\": \"consequatur\",
    \"new_mpin\": \"8107\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/reset-mpin"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "country_code": "consequatur",
    "mobile": "consequatur",
    "new_mpin": "8107"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-reset-mpin">
</span>
<span id="execution-results-POSTapi-reset-mpin" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-reset-mpin"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-reset-mpin"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-reset-mpin" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-reset-mpin">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-reset-mpin" data-method="POST"
      data-path="api/reset-mpin"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-reset-mpin', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-reset-mpin"
                    onclick="tryItOut('POSTapi-reset-mpin');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-reset-mpin"
                    onclick="cancelTryOut('POSTapi-reset-mpin');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-reset-mpin"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/reset-mpin</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-reset-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-reset-mpin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country_code"                data-endpoint="POSTapi-reset-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-reset-mpin"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>mobile</code> of an existing record in the user_logins table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_mpin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_mpin"                data-endpoint="POSTapi-reset-mpin"
               value="8107"
               data-component="body">
    <br>
<p>Must be 4 digits. Example: <code>8107</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-logout">POST api/logout</h2>

<p>
</p>



<span id="example-requests-POSTapi-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"consequatur\",
    \"device_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "consequatur",
    "device_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-logout">
</span>
<span id="execution-results-POSTapi-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-logout" data-method="POST"
      data-path="api/logout"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-logout"
                    onclick="tryItOut('POSTapi-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-logout"
                    onclick="cancelTryOut('POSTapi-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="POSTapi-logout"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>mobile</code> of an existing record in the user_logins table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-logout"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-profile">GET api/profile</h2>

<p>
</p>



<span id="example-requests-GETapi-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/profile" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/profile"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-profile">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-profile" data-method="GET"
      data-path="api/profile"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-profile"
                    onclick="tryItOut('GETapi-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-profile"
                    onclick="cancelTryOut('GETapi-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-dashboard">GET api/dashboard</h2>

<p>
</p>



<span id="example-requests-GETapi-dashboard">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/dashboard" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/dashboard"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-dashboard">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-dashboard" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-dashboard"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-dashboard"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-dashboard" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-dashboard">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-dashboard" data-method="GET"
      data-path="api/dashboard"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-dashboard', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-dashboard"
                    onclick="tryItOut('GETapi-dashboard');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-dashboard"
                    onclick="cancelTryOut('GETapi-dashboard');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-dashboard"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/dashboard</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-customer-list">GET api/customer/list</h2>

<p>
</p>



<span id="example-requests-GETapi-customer-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/customer/list" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/customer/list"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-customer-list">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-customer-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-customer-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-customer-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-customer-list" data-method="GET"
      data-path="api/customer/list"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-customer-list"
                    onclick="tryItOut('GETapi-customer-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-customer-list"
                    onclick="cancelTryOut('GETapi-customer-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-customer-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/customer/list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-customer-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-customer-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-customer-details--id-">GET api/customer/details/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-customer-details--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/customer/details/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/customer/details/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-customer-details--id-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-customer-details--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-customer-details--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-details--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-customer-details--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-details--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-customer-details--id-" data-method="GET"
      data-path="api/customer/details/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-details--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-customer-details--id-"
                    onclick="tryItOut('GETapi-customer-details--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-customer-details--id-"
                    onclick="cancelTryOut('GETapi-customer-details--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-customer-details--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/customer/details/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-customer-details--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-customer-details--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-customer-details--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the detail. Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-customer-filter">GET api/customer/filter</h2>

<p>
</p>



<span id="example-requests-GETapi-customer-filter">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/customer/filter" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/customer/filter"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-customer-filter">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-customer-filter" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-customer-filter"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-filter"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-customer-filter" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-filter">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-customer-filter" data-method="GET"
      data-path="api/customer/filter"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-filter', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-customer-filter"
                    onclick="tryItOut('GETapi-customer-filter');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-customer-filter"
                    onclick="cancelTryOut('GETapi-customer-filter');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-customer-filter"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/customer/filter</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-customer-filter"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-customer-filter"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-customer-store">POST api/customer/store</h2>

<p>
</p>



<span id="example-requests-POSTapi-customer-store">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/customer/store" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "prefix=vmqeopfuudtdsufvyvddq"\
    --form "name=amniihfqcoynlazghdtqt"\
    --form "email=andreanne00@example.org"\
    --form "phone_code=wbpilpmufinllwloauydl"\
    --form "phone=consequatur"\
    --form "country_code_alt_1=mqeopfuudtdsufvyvddqa"\
    --form "country_code_alt_2=mniihfqcoynlazghdtqtq"\
    --form "dob=2025-10-09T11:29:01"\
    --form "company_name=xbajwbpilpmufinllwloa"\
    --form "employee_rank=uydlsmsjuryvojcybzvrb"\
    --form "billing_address=consequatur"\
    --form "billing_landmark=mqeopfuudtdsufvyvddqa"\
    --form "billing_city=mniihfqcoynlazghdtqtq"\
    --form "billing_country=xbajwbpilpmufinllwloa"\
    --form "billing_pin=uydlsmsju"\
    --form "profile_image=@C:\Users\Dell\AppData\Local\Temp\php83CF.tmp" \
    --form "verified_video=@C:\Users\Dell\AppData\Local\Temp\php83E0.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/customer/store"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('prefix', 'vmqeopfuudtdsufvyvddq');
body.append('name', 'amniihfqcoynlazghdtqt');
body.append('email', 'andreanne00@example.org');
body.append('phone_code', 'wbpilpmufinllwloauydl');
body.append('phone', 'consequatur');
body.append('country_code_alt_1', 'mqeopfuudtdsufvyvddqa');
body.append('country_code_alt_2', 'mniihfqcoynlazghdtqtq');
body.append('dob', '2025-10-09T11:29:01');
body.append('company_name', 'xbajwbpilpmufinllwloa');
body.append('employee_rank', 'uydlsmsjuryvojcybzvrb');
body.append('billing_address', 'consequatur');
body.append('billing_landmark', 'mqeopfuudtdsufvyvddqa');
body.append('billing_city', 'mniihfqcoynlazghdtqtq');
body.append('billing_country', 'xbajwbpilpmufinllwloa');
body.append('billing_pin', 'uydlsmsju');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);
body.append('verified_video', document.querySelector('input[name="verified_video"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-customer-store">
</span>
<span id="execution-results-POSTapi-customer-store" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-customer-store"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-store"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-customer-store" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-store">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-customer-store" data-method="POST"
      data-path="api/customer/store"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-store', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-customer-store"
                    onclick="tryItOut('POSTapi-customer-store');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-customer-store"
                    onclick="cancelTryOut('POSTapi-customer-store');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-customer-store"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/customer/store</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-customer-store"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-customer-store"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="POSTapi-customer-store"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-customer-store"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-customer-store"
               value="andreanne00@example.org"
               data-component="body">
    <br>
<p>Must be a valid email address. Example: <code>andreanne00@example.org</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone_code"                data-endpoint="POSTapi-customer-store"
               value="wbpilpmufinllwloauydl"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>wbpilpmufinllwloauydl</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-customer-store"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code_alt_1</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country_code_alt_1"                data-endpoint="POSTapi-customer-store"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>alternative_phone_number_1</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="alternative_phone_number_1"                data-endpoint="POSTapi-customer-store"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code_alt_2</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country_code_alt_2"                data-endpoint="POSTapi-customer-store"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>alternative_phone_number_2</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="alternative_phone_number_2"                data-endpoint="POSTapi-customer-store"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dob</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="dob"                data-endpoint="POSTapi-customer-store"
               value="2025-10-09T11:29:01"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-09T11:29:01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="POSTapi-customer-store"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>employee_rank</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="employee_rank"                data-endpoint="POSTapi-customer-store"
               value="uydlsmsjuryvojcybzvrb"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>uydlsmsjuryvojcybzvrb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_address"                data-endpoint="POSTapi-customer-store"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="billing_landmark"                data-endpoint="POSTapi-customer-store"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_city"                data-endpoint="POSTapi-customer-store"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_country"                data-endpoint="POSTapi-customer-store"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_pin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="billing_pin"                data-endpoint="POSTapi-customer-store"
               value="uydlsmsju"
               data-component="body">
    <br>
<p>Must not be greater than 10 characters. Example: <code>uydlsmsju</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="POSTapi-customer-store"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 2048 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php83CF.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>verified_video</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="verified_video"                data-endpoint="POSTapi-customer-store"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Must not be greater than 10240 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php83E0.tmp</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-customer-update--id-">POST api/customer/update/{id}</h2>

<p>
</p>



<span id="example-requests-POSTapi-customer-update--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/customer/update/consequatur" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "prefix=vmqeopfuudtdsufvyvddq"\
    --form "name=amniihfqcoynlazghdtqt"\
    --form "phone_code=qxbajwbpi"\
    --form "phone=consequatur"\
    --form "country_code_alt_1=mqeopfuudtdsufvyvddqa"\
    --form "country_code_alt_2=mniihfqcoynlazghdtqtq"\
    --form "dob=2025-10-09T11:29:01"\
    --form "company_name=xbajwbpilpmufinllwloa"\
    --form "employee_rank=uydlsmsjuryvojcybzvrb"\
    --form "billing_address=consequatur"\
    --form "billing_landmark=mqeopfuudtdsufvyvddqa"\
    --form "billing_city=mniihfqcoynlazghdtqtq"\
    --form "billing_country=xbajwbpilpmufinllwloa"\
    --form "billing_pin=uydlsmsju"\
    --form "profile_image=@C:\Users\Dell\AppData\Local\Temp\php8400.tmp" \
    --form "verified_video=@C:\Users\Dell\AppData\Local\Temp\php8401.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/customer/update/consequatur"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('prefix', 'vmqeopfuudtdsufvyvddq');
body.append('name', 'amniihfqcoynlazghdtqt');
body.append('phone_code', 'qxbajwbpi');
body.append('phone', 'consequatur');
body.append('country_code_alt_1', 'mqeopfuudtdsufvyvddqa');
body.append('country_code_alt_2', 'mniihfqcoynlazghdtqtq');
body.append('dob', '2025-10-09T11:29:01');
body.append('company_name', 'xbajwbpilpmufinllwloa');
body.append('employee_rank', 'uydlsmsjuryvojcybzvrb');
body.append('billing_address', 'consequatur');
body.append('billing_landmark', 'mqeopfuudtdsufvyvddqa');
body.append('billing_city', 'mniihfqcoynlazghdtqtq');
body.append('billing_country', 'xbajwbpilpmufinllwloa');
body.append('billing_pin', 'uydlsmsju');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);
body.append('verified_video', document.querySelector('input[name="verified_video"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-customer-update--id-">
</span>
<span id="execution-results-POSTapi-customer-update--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-customer-update--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-customer-update--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-customer-update--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-customer-update--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-customer-update--id-" data-method="POST"
      data-path="api/customer/update/{id}"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-customer-update--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-customer-update--id-"
                    onclick="tryItOut('POSTapi-customer-update--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-customer-update--id-"
                    onclick="cancelTryOut('POSTapi-customer-update--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-customer-update--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/customer/update/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-customer-update--id-"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-customer-update--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="POSTapi-customer-update--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the update. Example: <code>consequatur</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>prefix</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prefix"                data-endpoint="POSTapi-customer-update--id-"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-customer-update--id-"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-customer-update--id-"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone_code"                data-endpoint="POSTapi-customer-update--id-"
               value="qxbajwbpi"
               data-component="body">
    <br>
<p>Must not be greater than 10 characters. Example: <code>qxbajwbpi</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-customer-update--id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code_alt_1</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country_code_alt_1"                data-endpoint="POSTapi-customer-update--id-"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>alternative_phone_number_1</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="alternative_phone_number_1"                data-endpoint="POSTapi-customer-update--id-"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country_code_alt_2</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country_code_alt_2"                data-endpoint="POSTapi-customer-update--id-"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>alternative_phone_number_2</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="alternative_phone_number_2"                data-endpoint="POSTapi-customer-update--id-"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dob</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="dob"                data-endpoint="POSTapi-customer-update--id-"
               value="2025-10-09T11:29:01"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-09T11:29:01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="POSTapi-customer-update--id-"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>employee_rank</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="employee_rank"                data-endpoint="POSTapi-customer-update--id-"
               value="uydlsmsjuryvojcybzvrb"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>uydlsmsjuryvojcybzvrb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_address"                data-endpoint="POSTapi-customer-update--id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="billing_landmark"                data-endpoint="POSTapi-customer-update--id-"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_city"                data-endpoint="POSTapi-customer-update--id-"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_country"                data-endpoint="POSTapi-customer-update--id-"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_pin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="billing_pin"                data-endpoint="POSTapi-customer-update--id-"
               value="uydlsmsju"
               data-component="body">
    <br>
<p>Must not be greater than 10 characters. Example: <code>uydlsmsju</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="POSTapi-customer-update--id-"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 2048 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php8400.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>verified_video</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="verified_video"                data-endpoint="POSTapi-customer-update--id-"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Must not be greater than 10240 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php8401.tmp</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-customer-order-list">GET api/customer/order/list</h2>

<p>
</p>



<span id="example-requests-GETapi-customer-order-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/customer/order/list" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/customer/order/list"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-customer-order-list">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-customer-order-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-customer-order-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-customer-order-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-customer-order-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-customer-order-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-customer-order-list" data-method="GET"
      data-path="api/customer/order/list"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-customer-order-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-customer-order-list"
                    onclick="tryItOut('GETapi-customer-order-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-customer-order-list"
                    onclick="cancelTryOut('GETapi-customer-order-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-customer-order-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/customer/order/list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-customer-order-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-customer-order-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-user-store">POST api/user/store</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-store">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/user/store" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=vmqeopfuudtdsufvyvddq"\
    --form "email=kunde.eloisa@example.com"\
    --form "phone=11613.31890586"\
    --form "whatsapp_no=11613.31890586"\
    --form "dob=2025-10-09T11:29:01"\
    --form "company_name=opfuudtdsufvyvddqamni"\
    --form "employee_rank=ihfqcoynlazghdtqtqxba"\
    --form "credit_limit=33"\
    --form "credit_days=79"\
    --form "gst_number=bpilpmufinllw"\
    --form "billing_address=consequatur"\
    --form "billing_landmark=mqeopfuudtdsufvyvddqa"\
    --form "billing_city=mniihfqcoynlazghdtqtq"\
    --form "billing_state=xbajwbpilpmufinllwloa"\
    --form "billing_country=uydlsmsjuryvojcybzvrb"\
    --form "billing_pin=yickznkyg"\
    --form "shipping_address=consequatur"\
    --form "shipping_landmark=mqeopfuudtdsufvyvddqa"\
    --form "shipping_city=mniihfqcoynlazghdtqtq"\
    --form "shipping_state=xbajwbpilpmufinllwloa"\
    --form "shipping_country=uydlsmsjuryvojcybzvrb"\
    --form "shipping_pin=yickznkyg"\
    --form "is_billing_shipping_same="\
    --form "profile_image=@C:\Users\Dell\AppData\Local\Temp\php8460.tmp" \
    --form "verified_video=@C:\Users\Dell\AppData\Local\Temp\php8471.tmp" \
    --form "gst_certificate_image=@C:\Users\Dell\AppData\Local\Temp\php8472.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user/store"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'vmqeopfuudtdsufvyvddq');
body.append('email', 'kunde.eloisa@example.com');
body.append('phone', '11613.31890586');
body.append('whatsapp_no', '11613.31890586');
body.append('dob', '2025-10-09T11:29:01');
body.append('company_name', 'opfuudtdsufvyvddqamni');
body.append('employee_rank', 'ihfqcoynlazghdtqtqxba');
body.append('credit_limit', '33');
body.append('credit_days', '79');
body.append('gst_number', 'bpilpmufinllw');
body.append('billing_address', 'consequatur');
body.append('billing_landmark', 'mqeopfuudtdsufvyvddqa');
body.append('billing_city', 'mniihfqcoynlazghdtqtq');
body.append('billing_state', 'xbajwbpilpmufinllwloa');
body.append('billing_country', 'uydlsmsjuryvojcybzvrb');
body.append('billing_pin', 'yickznkyg');
body.append('shipping_address', 'consequatur');
body.append('shipping_landmark', 'mqeopfuudtdsufvyvddqa');
body.append('shipping_city', 'mniihfqcoynlazghdtqtq');
body.append('shipping_state', 'xbajwbpilpmufinllwloa');
body.append('shipping_country', 'uydlsmsjuryvojcybzvrb');
body.append('shipping_pin', 'yickznkyg');
body.append('is_billing_shipping_same', '');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);
body.append('verified_video', document.querySelector('input[name="verified_video"]').files[0]);
body.append('gst_certificate_image', document.querySelector('input[name="gst_certificate_image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-store">
</span>
<span id="execution-results-POSTapi-user-store" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-store"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-store"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-user-store" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-store">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-user-store" data-method="POST"
      data-path="api/user/store"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-store', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-store"
                    onclick="tryItOut('POSTapi-user-store');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-store"
                    onclick="cancelTryOut('POSTapi-user-store');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-store"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user/store</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-user-store"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-user-store"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-user-store"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-user-store"
               value="kunde.eloisa@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Example: <code>kunde.eloisa@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="phone"                data-endpoint="POSTapi-user-store"
               value="11613.31890586"
               data-component="body">
    <br>
<p>Example: <code>11613.31890586</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>whatsapp_no</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="whatsapp_no"                data-endpoint="POSTapi-user-store"
               value="11613.31890586"
               data-component="body">
    <br>
<p>Example: <code>11613.31890586</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dob</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dob"                data-endpoint="POSTapi-user-store"
               value="2025-10-09T11:29:01"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-09T11:29:01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="POSTapi-user-store"
               value="opfuudtdsufvyvddqamni"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>opfuudtdsufvyvddqamni</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>employee_rank</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="employee_rank"                data-endpoint="POSTapi-user-store"
               value="ihfqcoynlazghdtqtqxba"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>ihfqcoynlazghdtqtqxba</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>credit_limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="credit_limit"                data-endpoint="POSTapi-user-store"
               value="33"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>33</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>credit_days</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="credit_days"                data-endpoint="POSTapi-user-store"
               value="79"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>79</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gst_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="gst_number"                data-endpoint="POSTapi-user-store"
               value="bpilpmufinllw"
               data-component="body">
    <br>
<p>Must not be greater than 15 characters. Example: <code>bpilpmufinllw</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_address"                data-endpoint="POSTapi-user-store"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="billing_landmark"                data-endpoint="POSTapi-user-store"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_city"                data-endpoint="POSTapi-user-store"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_state</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_state"                data-endpoint="POSTapi-user-store"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="billing_country"                data-endpoint="POSTapi-user-store"
               value="uydlsmsjuryvojcybzvrb"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>uydlsmsjuryvojcybzvrb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_pin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="billing_pin"                data-endpoint="POSTapi-user-store"
               value="yickznkyg"
               data-component="body">
    <br>
<p>Must not be greater than 10 characters. Example: <code>yickznkyg</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="shipping_address"                data-endpoint="POSTapi-user-store"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="shipping_landmark"                data-endpoint="POSTapi-user-store"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Initially nullable. Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="shipping_city"                data-endpoint="POSTapi-user-store"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Initially nullable. Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_state</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="shipping_state"                data-endpoint="POSTapi-user-store"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Initially nullable. Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="shipping_country"                data-endpoint="POSTapi-user-store"
               value="uydlsmsjuryvojcybzvrb"
               data-component="body">
    <br>
<p>Initially nullable. Must not be greater than 255 characters. Example: <code>uydlsmsjuryvojcybzvrb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_pin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="shipping_pin"                data-endpoint="POSTapi-user-store"
               value="yickznkyg"
               data-component="body">
    <br>
<p>Initially nullable. Must not be greater than 10 characters. Example: <code>yickznkyg</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="POSTapi-user-store"
               value=""
               data-component="body">
    <br>
<p>Initially nullable. Must be an image. Must not be greater than 2048 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php8460.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>verified_video</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="verified_video"                data-endpoint="POSTapi-user-store"
               value=""
               data-component="body">
    <br>
<p>Must be a file. Must not be greater than 10240 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php8471.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gst_certificate_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="gst_certificate_image"                data-endpoint="POSTapi-user-store"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 2048 kilobytes. Example: <code>C:\Users\Dell\AppData\Local\Temp\php8472.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_billing_shipping_same</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-user-store" style="display: none">
            <input type="radio" name="is_billing_shipping_same"
                   value="true"
                   data-endpoint="POSTapi-user-store"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-user-store" style="display: none">
            <input type="radio" name="is_billing_shipping_same"
                   value="false"
                   data-endpoint="POSTapi-user-store"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-user-list">GET api/user/list</h2>

<p>
</p>



<span id="example-requests-GETapi-user-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user/list" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user/list"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user-list">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user-list" data-method="GET"
      data-path="api/user/list"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user-list"
                    onclick="tryItOut('GETapi-user-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user-list"
                    onclick="cancelTryOut('GETapi-user-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user/list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-user-search">GET api/user/search</h2>

<p>
</p>



<span id="example-requests-GETapi-user-search">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user/search" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user/search"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user-search">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user-search" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user-search"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-search"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user-search" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-search">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user-search" data-method="GET"
      data-path="api/user/search"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user-search', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user-search"
                    onclick="tryItOut('GETapi-user-search');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user-search"
                    onclick="cancelTryOut('GETapi-user-search');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user-search"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user/search</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-user-show--id-">GET api/user/show/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-user-show--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user/show/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user/show/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user-show--id-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user-show--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user-show--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-show--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user-show--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-show--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user-show--id-" data-method="GET"
      data-path="api/user/show/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user-show--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user-show--id-"
                    onclick="tryItOut('GETapi-user-show--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user-show--id-"
                    onclick="cancelTryOut('GETapi-user-show--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user-show--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user/show/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user-show--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user-show--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-user-show--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the show. Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-category">GET api/category</h2>

<p>
</p>



<span id="example-requests-GETapi-category">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/category" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/category"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-category">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-category" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-category"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-category"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-category" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-category">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-category" data-method="GET"
      data-path="api/category"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-category', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-category"
                    onclick="tryItOut('GETapi-category');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-category"
                    onclick="cancelTryOut('GETapi-category');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-category"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/category</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-category"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-category"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-category-category-collection-wise--categoryid-">GET api/category/category-collection-wise/{categoryid}</h2>

<p>
</p>



<span id="example-requests-GETapi-category-category-collection-wise--categoryid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/category/category-collection-wise/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/category/category-collection-wise/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-category-category-collection-wise--categoryid-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-category-category-collection-wise--categoryid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-category-category-collection-wise--categoryid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-category-category-collection-wise--categoryid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-category-category-collection-wise--categoryid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-category-category-collection-wise--categoryid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-category-category-collection-wise--categoryid-" data-method="GET"
      data-path="api/category/category-collection-wise/{categoryid}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-category-category-collection-wise--categoryid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-category-category-collection-wise--categoryid-"
                    onclick="tryItOut('GETapi-category-category-collection-wise--categoryid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-category-category-collection-wise--categoryid-"
                    onclick="cancelTryOut('GETapi-category-category-collection-wise--categoryid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-category-category-collection-wise--categoryid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/category/category-collection-wise/{categoryid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-category-category-collection-wise--categoryid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-category-category-collection-wise--categoryid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>categoryid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="categoryid"                data-endpoint="GETapi-category-category-collection-wise--categoryid-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-collection">GET api/collection</h2>

<p>
</p>



<span id="example-requests-GETapi-collection">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/collection" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/collection"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-collection">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-collection" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-collection"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-collection"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-collection" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-collection">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-collection" data-method="GET"
      data-path="api/collection"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-collection', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-collection"
                    onclick="tryItOut('GETapi-collection');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-collection"
                    onclick="cancelTryOut('GETapi-collection');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-collection"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/collection</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-collection"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-collection"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-product-products-category-collection-wise">GET api/product/products-category-collection-wise</h2>

<p>
</p>



<span id="example-requests-GETapi-product-products-category-collection-wise">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/product/products-category-collection-wise" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/product/products-category-collection-wise"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-product-products-category-collection-wise">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-product-products-category-collection-wise" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-product-products-category-collection-wise"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-product-products-category-collection-wise"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-product-products-category-collection-wise" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-product-products-category-collection-wise">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-product-products-category-collection-wise" data-method="GET"
      data-path="api/product/products-category-collection-wise"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-product-products-category-collection-wise', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-product-products-category-collection-wise"
                    onclick="tryItOut('GETapi-product-products-category-collection-wise');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-product-products-category-collection-wise"
                    onclick="cancelTryOut('GETapi-product-products-category-collection-wise');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-product-products-category-collection-wise"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/product/products-category-collection-wise</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-product-products-category-collection-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-product-products-category-collection-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-product-products-collection-wise">GET api/product/products-collection-wise</h2>

<p>
</p>



<span id="example-requests-GETapi-product-products-collection-wise">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/product/products-collection-wise" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/product/products-collection-wise"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-product-products-collection-wise">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-product-products-collection-wise" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-product-products-collection-wise"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-product-products-collection-wise"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-product-products-collection-wise" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-product-products-collection-wise">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-product-products-collection-wise" data-method="GET"
      data-path="api/product/products-collection-wise"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-product-products-collection-wise', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-product-products-collection-wise"
                    onclick="tryItOut('GETapi-product-products-collection-wise');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-product-products-collection-wise"
                    onclick="cancelTryOut('GETapi-product-products-collection-wise');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-product-products-collection-wise"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/product/products-collection-wise</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-product-products-collection-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-product-products-collection-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-product-fabric--id-">GET api/product/fabric/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-product-fabric--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/product/fabric/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/product/fabric/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-product-fabric--id-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-product-fabric--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-product-fabric--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-product-fabric--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-product-fabric--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-product-fabric--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-product-fabric--id-" data-method="GET"
      data-path="api/product/fabric/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-product-fabric--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-product-fabric--id-"
                    onclick="tryItOut('GETapi-product-fabric--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-product-fabric--id-"
                    onclick="cancelTryOut('GETapi-product-fabric--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-product-fabric--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/product/fabric/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-product-fabric--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-product-fabric--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-product-fabric--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the fabric. Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-product-check-price">GET api/product/check/price</h2>

<p>
</p>



<span id="example-requests-GETapi-product-check-price">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/product/check/price" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/product/check/price"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-product-check-price">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-product-check-price" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-product-check-price"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-product-check-price"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-product-check-price" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-product-check-price">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-product-check-price" data-method="GET"
      data-path="api/product/check/price"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-product-check-price', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-product-check-price"
                    onclick="tryItOut('GETapi-product-check-price');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-product-check-price"
                    onclick="cancelTryOut('GETapi-product-check-price');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-product-check-price"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/product/check/price</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-product-check-price"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-product-check-price"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-product-measurement-product-wise">GET api/product/measurement-product-wise</h2>

<p>
</p>



<span id="example-requests-GETapi-product-measurement-product-wise">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/product/measurement-product-wise" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/product/measurement-product-wise"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-product-measurement-product-wise">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-product-measurement-product-wise" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-product-measurement-product-wise"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-product-measurement-product-wise"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-product-measurement-product-wise" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-product-measurement-product-wise">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-product-measurement-product-wise" data-method="GET"
      data-path="api/product/measurement-product-wise"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-product-measurement-product-wise', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-product-measurement-product-wise"
                    onclick="tryItOut('GETapi-product-measurement-product-wise');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-product-measurement-product-wise"
                    onclick="cancelTryOut('GETapi-product-measurement-product-wise');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-product-measurement-product-wise"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/product/measurement-product-wise</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-product-measurement-product-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-product-measurement-product-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-catalogue-list">GET api/catalogue/list</h2>

<p>
</p>



<span id="example-requests-GETapi-catalogue-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/catalogue/list" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/catalogue/list"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-catalogue-list">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-catalogue-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-catalogue-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-catalogue-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-catalogue-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-catalogue-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-catalogue-list" data-method="GET"
      data-path="api/catalogue/list"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-catalogue-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-catalogue-list"
                    onclick="tryItOut('GETapi-catalogue-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-catalogue-list"
                    onclick="cancelTryOut('GETapi-catalogue-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-catalogue-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/catalogue/list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-catalogue-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-catalogue-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-catalogue-pages">GET api/catalogue/pages</h2>

<p>
</p>



<span id="example-requests-GETapi-catalogue-pages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/catalogue/pages" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/catalogue/pages"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-catalogue-pages">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-catalogue-pages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-catalogue-pages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-catalogue-pages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-catalogue-pages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-catalogue-pages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-catalogue-pages" data-method="GET"
      data-path="api/catalogue/pages"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-catalogue-pages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-catalogue-pages"
                    onclick="tryItOut('GETapi-catalogue-pages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-catalogue-pages"
                    onclick="cancelTryOut('GETapi-catalogue-pages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-catalogue-pages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/catalogue/pages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-catalogue-pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-catalogue-pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-catalogue-page-item">GET api/catalogue/page/item</h2>

<p>
</p>



<span id="example-requests-GETapi-catalogue-page-item">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/catalogue/page/item" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/catalogue/page/item"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-catalogue-page-item">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-catalogue-page-item" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-catalogue-page-item"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-catalogue-page-item"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-catalogue-page-item" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-catalogue-page-item">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-catalogue-page-item" data-method="GET"
      data-path="api/catalogue/page/item"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-catalogue-page-item', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-catalogue-page-item"
                    onclick="tryItOut('GETapi-catalogue-page-item');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-catalogue-page-item"
                    onclick="cancelTryOut('GETapi-catalogue-page-item');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-catalogue-page-item"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/catalogue/page/item</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-catalogue-page-item"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-catalogue-page-item"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-catalogue-products-collection-wise">GET api/catalogue/products-collection-wise</h2>

<p>
</p>



<span id="example-requests-GETapi-catalogue-products-collection-wise">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/catalogue/products-collection-wise" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/catalogue/products-collection-wise"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-catalogue-products-collection-wise">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-catalogue-products-collection-wise" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-catalogue-products-collection-wise"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-catalogue-products-collection-wise"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-catalogue-products-collection-wise" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-catalogue-products-collection-wise">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-catalogue-products-collection-wise" data-method="GET"
      data-path="api/catalogue/products-collection-wise"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-catalogue-products-collection-wise', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-catalogue-products-collection-wise"
                    onclick="tryItOut('GETapi-catalogue-products-collection-wise');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-catalogue-products-collection-wise"
                    onclick="cancelTryOut('GETapi-catalogue-products-collection-wise');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-catalogue-products-collection-wise"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/catalogue/products-collection-wise</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-catalogue-products-collection-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-catalogue-products-collection-wise"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-order-video-store">POST api/order/video/store</h2>

<p>
</p>



<span id="example-requests-POSTapi-order-video-store">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/order/video/store" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"verified_video\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/order/video/store"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "verified_video": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-order-video-store">
</span>
<span id="execution-results-POSTapi-order-video-store" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-order-video-store"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-order-video-store"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-order-video-store" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-order-video-store">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-order-video-store" data-method="POST"
      data-path="api/order/video/store"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-order-video-store', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-order-video-store"
                    onclick="tryItOut('POSTapi-order-video-store');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-order-video-store"
                    onclick="cancelTryOut('POSTapi-order-video-store');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-order-video-store"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/order/video/store</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-order-video-store"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-order-video-store"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>verified_video</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="verified_video"                data-endpoint="POSTapi-order-video-store"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-order-list">GET api/order/list</h2>

<p>
</p>



<span id="example-requests-GETapi-order-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/order/list" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/order/list"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-order-list">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-order-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-order-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-order-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-order-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-order-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-order-list" data-method="GET"
      data-path="api/order/list"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-order-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-order-list"
                    onclick="tryItOut('GETapi-order-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-order-list"
                    onclick="cancelTryOut('GETapi-order-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-order-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/order/list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-order-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-order-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-order-detail">GET api/order/detail</h2>

<p>
</p>



<span id="example-requests-GETapi-order-detail">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/order/detail" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/order/detail"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-order-detail">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-order-detail" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-order-detail"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-order-detail"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-order-detail" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-order-detail">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-order-detail" data-method="GET"
      data-path="api/order/detail"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-order-detail', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-order-detail"
                    onclick="tryItOut('GETapi-order-detail');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-order-detail"
                    onclick="cancelTryOut('GETapi-order-detail');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-order-detail"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/order/detail</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-order-detail"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-order-detail"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-order-ledger-view">GET api/order/ledger-view</h2>

<p>
</p>



<span id="example-requests-GETapi-order-ledger-view">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/order/ledger-view" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/order/ledger-view"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-order-ledger-view">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-order-ledger-view" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-order-ledger-view"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-order-ledger-view"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-order-ledger-view" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-order-ledger-view">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-order-ledger-view" data-method="GET"
      data-path="api/order/ledger-view"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-order-ledger-view', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-order-ledger-view"
                    onclick="tryItOut('GETapi-order-ledger-view');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-order-ledger-view"
                    onclick="cancelTryOut('GETapi-order-ledger-view');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-order-ledger-view"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/order/ledger-view</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-order-ledger-view"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-order-ledger-view"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-order-payment-receipt-save">POST api/order/payment-receipt-save</h2>

<p>
</p>



<span id="example-requests-POSTapi-order-payment-receipt-save">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/order/payment-receipt-save" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/order/payment-receipt-save"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-order-payment-receipt-save">
</span>
<span id="execution-results-POSTapi-order-payment-receipt-save" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-order-payment-receipt-save"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-order-payment-receipt-save"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-order-payment-receipt-save" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-order-payment-receipt-save">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-order-payment-receipt-save" data-method="POST"
      data-path="api/order/payment-receipt-save"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-order-payment-receipt-save', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-order-payment-receipt-save"
                    onclick="tryItOut('POSTapi-order-payment-receipt-save');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-order-payment-receipt-save"
                    onclick="cancelTryOut('POSTapi-order-payment-receipt-save');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-order-payment-receipt-save"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/order/payment-receipt-save</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-order-payment-receipt-save"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-order-payment-receipt-save"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
