import { createGlobalStyle } from "styled-components";

/*
const Global = createGlobalStyle`
    :root{
        --primary: #FFE132;
        --secondary: #2f2c2c;
        --success: #202124;
    }
    *{
        margin: 0;
        padding: 0;
    }
    ul{
        margin: 0;
    }

    .btn-secondary{
        color: #000;
        font-weight: bold;
        background: var(--secondary);
    }
`;
*/
const Global = createGlobalStyle`
    :root{
        --darkPrimary: #202124;
        --gray: #dadada;
        --grayLight: #f4f4f4;
        --primary: #ffe132;
        --primaryOpacity: #ffed84;
        --blue: #0047ff;
        --blueLink: #0066cc;
        --white: white;
        --success: #86ffa8;
        --green: #326741;
        --danger: #e02611;
        --red: #78362f;
    }
`;

export default Global;
