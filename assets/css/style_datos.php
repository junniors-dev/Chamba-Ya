<style>

    body{
        background: linear-gradient(120deg, #e2e2e2, #1D3D6E);
    }

    .form-container {
        max-width: 750px;
        margin: 20px auto;
        padding: 40px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .form-container h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-container h3 {
        text-align: center;
        margin-bottom: 30px;
        color: #666;
    }

    .form-container label {
        font-size: 16px;
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-container .profile-picture p{
        font-size: 12px;
        margin-bottom: 20px;
        color: #000;
    }

    .form-container .profilePictureImage{
        display: flex;
        justify-content: center;
        display: none;
    }

    .form-container .profile-picture img{
        justify-content: center;
        object-fit: cover;
        width: 400px;
        margin-top: 10px;
        margin-bottom: 15px;
        visibility: hidden;
    }

    .form-container input[type="text"],
    .form-container input[type="tel"],
    .form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    
    .form-container input[type="file"]{
        width: 100%;
        padding: 10px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-container textarea {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        field-sizing: content;
        resize: none;
    }

    .form-container .descripcionPerfil {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .form-row .form-group {
        flex: 1;
        min-width: 150px;
    }

    .form-row .form-group input, 
    .form-row .form-group select {
        width: 100%;
    }

    .form-container .form_buttons {
        display: flex;
        gap: 20px;
    }

    .form-container button {
        width: 100%;
        padding: 15px;
        font-size: 14px;
        background-color: #1D3D6E;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #0056b3;
    }
</style>