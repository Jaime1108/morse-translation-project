<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Morse Code Translation</title>
    <nav>
        <div>Morse Code Translator</div>
    </nav>
    <style>
        nav {
            width: 100%;
            background-color:rgb(234, 172, 0);
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .input-text{
            font-size: 18px;
            height: 100px;
            padding:10px;
            width:95%;
            resize: none;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 16px;
            font-size: 1em;
            background-color:rgb(0, 82, 234);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
        }
        .tabs {
            display: flex;
            flex-wrap: wrap;
            max-width: 700px;
            font-family: sans-serif;
        }
        .tab__label {
            padding: 10px 16px;
            cursor: pointer;
        }
        .tab__radio {
            display: none;
        }
        .tab__content {
            order: 1;
            width: 100%;
            border-bottom: 3px solid #dddddd;
            line-height: 1.5;
            font-size: 0.9em;
            display: none;
        }
        .tab__radio:checked + .tab__label {
            font-weight: bold;
            color: green;
            border-bottom: 2px solid teal; 
        }
        .tab__radio:checked + .tab__label + .tab__content {
            display: initial;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="tabs">
        <input type="radio" class="tab__radio" name="tab" id="text-to-morse" checked>
        <label for="text-to-morse" class="tab__label">Text to Morse Code</label>
        <div class="tab__content">
            <form id="textToMorseForm">
                <textarea class="input-text" type="text" id="textToMorse" class="entered-text" placeholder="Enter text to convert"></textarea>
                <button type="submit">Translate</button>
            </form>
            <p><strong>Translation:</strong> <span id="morseResult"></span></p>
        </div>

        <input type="radio" class="tab__radio" name="tab" id="morse-to-text">
        <label for="morse-to-text" class="tab__label">Morse Code to Text</label>
        <div class="tab__content">
            <form id="morseToTextForm">
                <textarea class="input-text" type="text" id="morseToText" class="entered-text" placeholder="Enter Morse code to convert"></textarea>
                <button type="submit">Translate</button>
            </form>
            <p><strong>Translation:</strong> <span id="textResult"></span></p>
        </div>

        <input type="radio" class="tab__radio" name="tab" id="Encryption">
        <label for="Encryption" class="tab__label">Text Encryption</label>
        <div class="tab__content">
            <form id="TextEncryptionForm">
                <textarea class="input-text" type="text" id="TextEncryption" class="entered-text" placeholder="Enter your text encrypt"></textarea>
                <button type="submit">Encrypt</button>
            </form>
            <p><strong>Encrypted Text:</strong> <span id="encryptResult"></span></p>
        </div>

        <input type="radio" class="tab__radio" name="tab" id="Decryption">
        <label for="Decryption" class="tab__label">Text Decryption</label>
        <div class="tab__content">
            <form id="TextDecryptionForm">
                <textarea class="input-text" type="text" id="TextDecryption" class="entered-text" placeholder="Enter Encrypted text"></textarea>
                <input type="text" id="DecryptKey" placeholder="Enter the key" class="entered-text">
                <input type="text" id="DecryptIV" placeholder="Enter the IV" class="entered-text">
                <button type="submit">Decrypt</button>
            </form>
            <p><strong>Decrypted Message: </strong> <span id="decryptResult"></span></p>
        </div>
    </div>
    </div>
</body>
<script>
    document.getElementById("textToMorseForm").addEventListener("submit", function (event) {
        event.preventDefault();
        const input = document.getElementById("textToMorse").value;
        translate("text_to_morse", input);
    });

    document.getElementById("morseToTextForm").addEventListener("submit", function (event) {
        event.preventDefault();
        const input = document.getElementById("morseToText").value;
        translate("morse_to_text", input);
    });

    document.getElementById("TextEncryptionForm").addEventListener("submit", function (event){
        event.preventDefault();
        const input = document.getElementById("TextEncryption").value;
        translate("text_encryption",input);
    });
    document.getElementById("TextDecryptionForm").addEventListener("submit", function(event){
        event.preventDefault();
        const input = document.getElementById("TextDecryption").value;
        const key = document.getElementById("DecryptKey").value;
        const IV = document.getElementById("DecryptIV").value;
        translate("text_decryption",input, key, IV);
    });

    function translate(action, input, key="", IV="") {
        // Clear previous results
        if (action === "text_to_morse") {
            document.getElementById("morseResult").innerText = "Translating...";
        } else if (action === "morse_to_text") {
            document.getElementById("textResult").innerText = "Translating...";
        } else if (action === "text_encryption") {
            document.getElementById("encryptResult").innerText = "Encrypting...";
        } else if (action === "text_decryption") {
            document.getElementById("decryptResult").innerText = "Decrypting text...";
        }

        // Create AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "process.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (action === "text_to_morse") {
                    document.getElementById("morseResult").innerText = xhr.responseText;
                } else if (action === "morse_to_text") {
                    document.getElementById("textResult").innerText = xhr.responseText;
                } else if (action === "text_encryption") {
                    document.getElementById("encryptResult").innerText = xhr.responseText;
                } else {
                    document.getElementById("decryptResult").innerText = xhr.responseText;
                }
            }
        };

        // Send the request with the action and input value
        if (action === "text_decryption"){
            xhr.send(`action=${action}&text_input=${encodeURIComponent(input)}&key=${encodeURIComponent(key)}&IV=${encodeURIComponent(IV)}`)
        } else {
        xhr.send(`action=${action}&text_input=${encodeURIComponent(input)}`);
        }
    }
</script>
<footer>
&copy; 2025 Morse Code Translator
</footer>
