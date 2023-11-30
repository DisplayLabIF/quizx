import React from "react";

import { Container } from "./styles";

function Checkbox({ checked, onChange = () => {} }) {
  return (
    <Container className="checkbox">
      <input
        checked={checked}
        type="checkbox"
        onChange={onChange}
      />
      <span className="checkmark"></span>
    </Container>
  );
}

export default Checkbox;
