<style>
    
    header a{
        text-decoration: none;
        color: #000;
    }

    .menu-toggle{
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        width: 30px;
        height: 30px;
        padding: 0;
    }

    .menu-toggle .bar{
        width: 100%;
        height: 3px;
        background-color: #343a40;
        margin: 5px 0;
        transition: 0.4s;
        border-radius: 2px;
    }

    .main-header{
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 30px;
        background-color: none;
        position:sticky;
        top: 0;
        z-index: 1000;
    }

    .main-nav {
        display: flex;
        gap: 60px;
    }

    .nav-links{
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 100px;
    }

    .nav-links li{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .actions{
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .search_box {
        position: relative;
        width: 300px;
    }

    .search_box input{
        width: 100%;
        padding: 10px 45px 10px 20px; 
        border: 1px solid #ccc;
        border-radius: 25px; 
        outline: none;
        font-size: 16px;
    }

    .search_box i {
        position: absolute;
        right: 15px; 
        top: 50%;
        transform: translateY(-50%);
        color: #000;
    }

    .user_box{
        position: relative;
    }

    .user_menu{
        position: absolute;
        top: 70px;
        right: 0;
        background: #fff;
        border: 2px solid #000;
        border-radius: 10px;
        padding: 10px 0;
        width: 200px;

        opacity: 0;
        visibility: hidden;
        transition: .3s;
    }

    .user_menu.active{
        opacity: 1;
        visibility: visible;
    }

    .user_menu a{
        display: block;
        padding: 10px;
        color: #000;
        text-decoration: none;
    }

    .user_menu a:hover{
        background: #f0f0f0;
    }

    .user_menu p{
        padding: 10px;
        margin: 0;
        font-weight: bold;
    }

    .user_box button{
        background-color : #EADDFF;
        border: none;
        padding: 20px;
        border-radius: 32px;
        cursor: pointer;
    }
    .user_box button i{
        color: #000;
        font-size: 25px;
    }

    @media (max-width: 768px) {
        .main-header{
            flex-wrap: wrap;
            padding: 15px 20px;
        }

        .logo_web{
            width: 150px;
        }

        .menu-toggle{
            display: block;
        }

        .main-nav{
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            background: #fff;
            transform: translateY(-100vh);
            transition: .4s;
            opacity: 0;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            padding: 0;
            gap : 0;
            z-index: 999;
        }

        .main-nav.open{
            transform: translateY(0);
            opacity: 1;
            pointer-events: all;
        }

        .nav-links{
            flex-direction: column;
            gap: 0;
            width: 100%;
        }

        .nav-links li{
            width: 100%;
            border-bottom: 1px solid #eee;
            justify-content: left;
            padding: 10px;
        }

        .nav-links a{
            display: block;
            padding: 15px 20px;
        }

        .actions{
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 20px;
            gap: 15px;
        }

        .search_box{
            width: 100%;
        }

        .search_box input{
            width: 100%;
        }

        .user_box{
            width: 100%;
        }

        .user_box button{
            width: 100%;
            border-radius: 10px;
            padding: 12px;
        }

        .user_menu{
            position: static;
            width: 100%;
            opacity: 1;
            visibility: visible;
            border: 1px solid #ddd;
            margin-top: 10px;
            display: none;
        }

        .user_menu.active{
            display: block;
        }
    }
</style>