import React from 'react'
import { BiImageAdd } from 'react-icons/bi';

export default function imagemOpcao({opcao, index, upload}) {
    return (
        <div 
            style={{
                display: opcao.texto && opcao.texto !== '' ? 'none' : 'block',
                width: opcao.imagem !== undefined && opcao.imagem && '100%'
            }}
        >
            <input
                type="file"
                accept="image/*"
                id={`image-upload-opcao_${opcao.id}`}
                style={{ display: 'none' }}
                onChange={e =>
                upload(e, index)
                }
            />
            { opcao.imagem !== undefined && opcao.imagem ? 
                <label
                htmlFor={`image-upload-opcao_${opcao.id}`}
                className="btn pr-0"
                >
                <img
                    style={{
                    maxHeight: 45,
                    maxWidth: 45
                    }}
                    className="img-fluid"
                    src={opcao.imagem}
                />
                
                </label>
            : 
            <>
                <label
                id={`label-image-upload-opcao_${opcao.id}`}
                htmlFor={`image-upload-opcao_${opcao.id}`}
                className="btn m-0 pr-0"
                >
                    <BiImageAdd size={24} color={'#666666'}/>
                </label>
                <div
                id={`feedback-image-upload-opcao_${opcao.id}`}
                style={{
                    display: 'none'
                }}
                >

                </div>
            </>
            }
        </div>
    )
}
