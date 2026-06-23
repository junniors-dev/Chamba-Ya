<style>
    /*==============FOOTER==============*/
    .footer{
        background-color: #fff;
        padding: 40px 0;
        margin-top: 40px;
        border-top: 1px solid #e5e7eb;
        font-family: Arial, Helvetica, sans-serif;
    }

    .footer .footer_container{
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer .footer_row{
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 120px;
    }

    .footer .footer_links{
        flex: none;
        width: auto;
        text-align: left;
        padding: 0 15px;
    }

    .footer .footer_links h4{
        font-size: 1.13em;
        color: black;
        margin-bottom: 25px;
        font-weight: 500;
        border-bottom: 3px solid #015eafee;
        display: inline-block;
        padding-bottom: 10px;
    }

    .footer .footer_links ul{
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .footer .footer_links ul li a{
        font-size: 1.06em;
        text-decoration: none;
        color: rgb(85, 85, 85);
        display: block;
        margin-bottom: 15px;
        transition: all .3s ease;
    }

    .footer .footer_links ul li a:hover{
        color: black;
        transform: translateX(6px);
    }

    .footer .social_links a{
        display: inline-block;
        min-height: 40px;
        width: 40px;
        margin: 0 10px 10px 0;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        color: black;
        transition: all .5s ease;
    }

    @media (max-width: 768px) {
        .footer .footer_row{
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }
        .footer .footer_links{ text-align: center; }
        .footer .footer_links h4{ font-size: 1.3em; }
        .footer .footer_links ul li a{ font-size: 1.2em; }
        .footer .social_links a{ font-size: 1.2em; }
    }
</style>
