import React, { useState, useEffect, useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { Container, ImagePreview, ImageUpload } from './styles';
import apiQuizClass from './../../../services/apiQuizClass'
import MultiSelectTextInput from '../../Select/MultiSelectTextInput';
import { ScaleLoader } from 'react-spinners';
import actionsQuiz from '../../../actions/Quiz';
import DragAndDrop from "../../DragAndDrop";
import QuestoesList from './QuestoesList';
import produce from 'immer';
import { FaTimes } from 'react-icons/fa';

function Header({quiz, finalizarQuiz}) {
    const dispatch = useDispatch();

    const [uploadFeedback, setUploadFeedback] = useState('');

    async function save(excluirImage = false) {
        document.getElementById('box-salvando').style.display='block';
        let data ={
            nome: quiz.nome,
            assuntos: quiz.assuntos,
            nivel: quiz.nivel, 
            image: excluirImage ? '' : quiz.image,
            id: quiz.id,
            questoes: quiz.questoes
        };

        try {
            const response = !data.id
            ? await apiQuizClass.post('/quizes', data)
            : await apiQuizClass.put(`/quizes/${data.id}`, data);
            if(response.data.quiz)
                dispatch(actionsQuiz.setQuiz(response.data.quiz));
            // dispatch(actionsQuiz.setQuizId(response.data.id_quiz));
            if(response.data.id_session){
                localStorage.setItem('@QuizClassSessionId', response.data.id_session);
            }
            document.getElementById('box-salvando').style.display='none';
            if(response.status !== 200){
                document.getElementById('box-salvando-erro').style.display='block';
                setTimeout(function() {
                    document.getElementById('box-salvando-erro').style.display='none';
                }, 2000);
            }
                
        } catch (e) {
            console.log(e)
            document.getElementById('box-salvando').style.display='none';
            document.getElementById('box-salvando-erro').style.display='block';
            setTimeout(function() {
                document.getElementById('box-salvando-erro').style.display='none';
            }, 2000);
        }        
    }

    async function uploadImage(e) {
        document.getElementById('box-salvando').style.display='block';
        const file = e.target.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('quiz_id', quiz.id);
        setUploadFeedback(
            <ScaleLoader height={10} width={17} margin={0} color="#333" />
        );

        try {
            const response = await apiQuizClass.post('/upload', formData);
            dispatch(actionsQuiz.setQuizImage(response.data.url));
            dispatch(actionsQuiz.setQuizId(response.data.quiz_id));
            setUploadFeedback('');
            document.getElementById('box-salvando').style.display='none';
        } catch (e) {
            setUploadFeedback('Erro no upload!');
            document.getElementById('box-salvando').style.display='none';
        }
    }

    function addQuestao() {
        dispatch(actionsQuiz.setQuizAddQuestao());
    }

    async function saveReordenar(questoesReordenadas) {
        document.getElementById('box-salvando').style.display='block';
        let data ={
            nome: quiz.nome,
            assuntos: quiz.assuntos,
            nivel: quiz.nivel, 
            image: quiz.image,
            id: quiz.id,
           questoes: questoesReordenadas
        };

        try {
            await apiQuizClass.put(`/quizes/${data.id}`, data);
            document.getElementById('box-salvando').style.display='none';
        } catch (e) {
            document.getElementById('box-salvando').style.display='none';
            document.getElementById('box-salvando-erro').style.display='block';
            setTimeout(function() {
                document.getElementById('box-salvando-erro').style.display='none';
            }, 2000);
        }         
    }

    const moveItem = useCallback(
        (from, to) => {
            const reordenar = produce(quiz.questoes, draft => {
                const dragged = draft[from];
                draft.splice(from, 1);
                draft.splice(to, 0, dragged);
            });
            dispatch(actionsQuiz.setQuizQuestoes(reordenar));
            dispatch(actionsQuiz.setQuizQuestaoSelected(to));
            saveReordenar(reordenar);
        },
        [quiz.questoes]
    );
    
return <Container>
    <div className="row">
        <div className="col-12 col-lg-4 col-xl-4">
            <label>Nome</label>
        <input 
            type="text" 
            name="name"
            maxLength="100"
            className="form-control form-default" 
            placeholder="Dê um título, como: Biologia - Evolução" 
            onChange={e =>  dispatch(actionsQuiz.setQuizNome(e.target.value))}
            defaultValue={quiz.nome}
            onBlur={() => save(false)}></input>
        </div>
        <div className="col-12 col-lg-3 col-xl-3">
            <label>Categoria/Nível</label>
            <select 
                type="text" 
                className="form-select form-default form-control" 
                onChange={e => dispatch(actionsQuiz.setQuizNivel(e.target.value))}
                value={quiz.nivel}
                onBlur={() => save(false)}
            >
                <option value="NONE">Sem categoria</option>
                <optgroup label="Ensino Regular">
                    <option value="FUNDAMENTAL_1">Fundamental 1</option>
                    <option value="FUNDAMENTAL_2">Fundamental 2</option>
                    <option value="ENSINO_MEDIO">Ensino médio</option>
                    <option value="ENSINO_TECNICO">Ensino técnico</option>
                    <option value="GRADUACAO">Graduação</option>
                    <option value="POS_GRADUACAO">Pós-graduação</option>
                </optgroup>
                <optgroup label="Diversidades">
                    <option value="CURIOSIDADES">Curiosidades</option>
                    <option value="FILMES_SERIES_ANIMES">Filmes, Séries e Animes</option>
                    <option value="TV">TV</option>
                </optgroup>
                
            </select>
        </div>
        <div className="col-12 col-lg-3 col-xl-3">
            <label>Assuntos</label>
            <MultiSelectTextInput 
                value={quiz.assuntos} 
                setValue={e=>dispatch(actionsQuiz.setQuizAssuntos(e))}
                placeholder={'Ex.: Biologia, Genética, Evolução'}
                onBlur={() => save(false)}
            />
        </div>
        <div className="col-12 col-lg-2 col-xl-2 pt-4 d-flex flex-row align-items-center">
            <ImageUpload
                htmlFor="image-upload-quiz"
                className="btn btn-default shadow-default btn-block d-flex flex-column justify-content-center align-items-center"
                style={{
                    lineHeight: '100%',
                    width: '100%'
                }}
            >
                <input
                type="file"
                accept="image/*"
                id="image-upload-quiz"
                style={{ display: 'none' }}
                onChange={e =>
                    uploadImage(e)
                }
                />
                {uploadFeedback === ''
                    ? quiz.image && quiz.image !== undefined && quiz.image !== '' ?
                    <>
                        <ImagePreview
                            className="image__img"
                            style={{
                                display:
                                quiz.image && quiz.image !== undefined
                                    ? 'block'
                                    : 'none'
                            }}
                            src={
                                quiz.image
                            }
                        />
                        <div className="image__overlay border-radius">
                            <i className="fas fa-image"></i> Alterar imagem
                        </div>
                    </>
                    :
                    <span><i className="fas fa-image"></i> Adicionar uma imagem</span>
                : uploadFeedback
                }
            </ImageUpload>
            { uploadFeedback === '' && quiz.image && quiz.image !== undefined && quiz.image !== '' &&
                <div 
                    className="remover-imagem"  
                    title="Remover imagem"
                    onClick={()=>{
                        save(true).then(()=>{
                            dispatch(actionsQuiz.setQuizImage(''));
                        })
                    }}
                >
                    <FaTimes size={13} />
                </div>
            }
        </div>
    </div>
    <div className="row pt-4" style={{paddingLeft: '15px', paddingRight:'15px'}}>
        <div className="mr-auto" style={{marginTop: 22}}>
            <button type="button" className="btn btn-default shadow-default mr-2"  onClick={addQuestao}><i className="fas fa-plus"></i> Nova questão</button>
            <button className="btn btn-success shadow-default" onClick={finalizarQuiz} disabled={quiz.id ? false : true}><i className="fas fa-check "></i> Finalizar Quiz</button>
        </div>
        { quiz.questoes.length > 0 &&
        <div>
            <label style={{fontSize: '10px', fontWeight: 'normal'}}>QUESTÕES:</label><br/>
            <DragAndDrop>
                <div className="row" style={{paddingLeft: 11, paddingRight: 15}}>
                    {quiz.questoes.map((questao, index) => (
                            <QuestoesList 
                                key={questao.id}
                                className="row"
                                questao={questao} 
                                index={index} 
                                questaoSelected={quiz.questaoSelected} 
                                moveItem={moveItem}
                            />
                    ))}
                </div>
            </DragAndDrop>

        </div>
        }
    </div>
</Container>;
}

export default Header;