<style>
    
    header a{
        text-decoration: none;
        color: #000;
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


</style>