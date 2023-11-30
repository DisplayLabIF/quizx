import React from "react";

import { Container } from "./styles";

function SwitchCheckbox({ onChange = () => {}, checked}) {
  return (
    <Container>
      <input
        type="checkbox"
        onChange={onChange}
        checked={checked}
      />
      <span className="slider round"></span>
    </Container>
  );
}

export default SwitchCheckbox;
