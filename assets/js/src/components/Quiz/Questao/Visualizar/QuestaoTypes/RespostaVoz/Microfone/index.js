import React, { useState, useEffect, useCallback } from 'react';
import { FaMicrophone, FaStop } from 'react-icons/fa';

import { Container, Mic, Alert } from './styles';
import Loader from "react-loader-spinner";

function Microfone({
  onStop,
  onRecording,
  onResult,
  setShowFeedback,
  style = {},
  disable = false
}) {
  const [mediaRecorder, setMediaRecorder] = useState(null);

  const [gravando, setGravando] = useState(false);
  const [loadingTranslate, setLoadingTranslate] = useState(false);
  const [iconAudio, setIconAudio] = useState('');
  const [showAlert, setShowAlert] = useState(false);
  const [recognition, setRecognition] = useState(null);

  const [useSpeechRecognitionOrMediaRecorder, setUseSpeechRecognitionOrMediaRecorder] = useState();

  function detectSilence(
    stream,
    onSoundEnd = _ => {},
    onSoundStart = _ => {},
    silence_delay = 1000, //ms
    min_decibels = -70 //decibéis
  ) {
    const ctx = new AudioContext();
    const analyser = ctx.createAnalyser();
    const streamNode = ctx.createMediaStreamSource(stream);
    streamNode.connect(analyser);
    analyser.minDecibels = min_decibels;

    const data = new Uint8Array(analyser.frequencyBinCount);
    let silence_start = performance.now();
    let triggered = false; // aciona apenas uma vez por evento de silêncio

    function loop(time) {
      requestAnimationFrame(loop);
      analyser.getByteFrequencyData(data);
      if (data.some(v => v)) {
        // se houver dados acima do limite de db fornecido
        if (triggered) {
          triggered = false;
          onSoundStart();
        }
        silence_start = time;
      }
      if (!triggered && time - silence_start > silence_delay) {
        onSoundEnd();
        triggered = true;
      }
    }
    loop();
  }

  function createRecognition(language = "en_US") {
    const SpeechRecognition =
      window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition =
      SpeechRecognition !== undefined ? new SpeechRecognition() : null;
  
    if (!recognition) {
      setShowAlert(true)
      return null;
    }
  
    recognition.lang = language;
    recognition.continous = true;
  
    recognition.onstart = () => {
      setGravando(true);
      if (onRecording !== undefined) {
        onRecording();
      }
    };
    recognition.onend = () => {
      setGravando(false);
      if (onStop !== undefined) {
        onStop();
      }
    };
    recognition.onerror = (e) => console.log("error", e);
    recognition.onresult = (event) => {
      setLoadingTranslate(true);
      setShowFeedback(false);
      onResult(event.results[0][0].transcript, getTypeRecording(), null).then( () => {
        setGravando(false);
        setLoadingTranslate(false);
      })
      .catch(e => {
        console.log(e);
        onStop();
        setGravando(false);
        setLoadingTranslate(false);
        setShowFeedback(true);
      });
      

    };
  
    return recognition;
  }

  const recognitionClickGravar = e => {
    if (!recognition)
      return (setShowAlert(true));

    gravando ? recognition.stop() : recognition.start();

  }

  const mediaRecorderClickGravar = e => {
    e.preventDefault();

    const browserIsSafari =
      /constructor/i.test(window.HTMLElement) ||
      (function(p) {
        return p.toString() === '[object SafariRemoteNotification]';
      })(
        !window['safari'] ||
          (typeof safari !== 'undefined' && window['safari'].pushNotification)
      );

    if (browserIsSafari) return setShowAlert(true);

    if (gravando === false) {
      setGravando(true);
      if (onRecording !== undefined) {
        onRecording();
      }
      document
        .getElementById(e.currentTarget.id)
        .childNodes[0].classList.add('gravando');
      navigator.mediaDevices.getUserMedia({ audio: true }).then(
        stream => {
          if (mediaRecorder === null)
            setMediaRecorder(new MediaRecorder(stream));
        },
        err => {
          console.log(err);
        }
      );
    } else {
      setGravando(false);
      document
        .getElementById(e.currentTarget.id)
        .childNodes[0].classList.remove('gravando');
      if (mediaRecorder.state === 'recording') mediaRecorder.stop();
      setLoadingTranslate(true);
    }
  };

  const onMediaRecorderChange = useCallback(() => {
    let chunks = [];

    function onSilence() {
      if (mediaRecorder !== null && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
      }
    }

    function onSpeak() {
      //console.log('speaking');
    }

    if (mediaRecorder !== null) {
      detectSilence(mediaRecorder.stream, onSilence, onSpeak);
      mediaRecorder.ondataavailable = data => {
        chunks.push(data.data);
        if (mediaRecorder.state === 'recording') mediaRecorder.stop();
      };
      mediaRecorder.onstop = () => {
        setLoadingTranslate(true);
        setGravando(false);
        setShowFeedback(false);

        if (onStop !== undefined) {
          onStop();
        }
        const blob = new Blob(chunks, { type: 'audio/ogg; code=opus' });
        const blobUrl = URL.createObjectURL(blob);
        const reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = () => {
          let audioData = reader.result.replace(
            /^data:audio\/ogg; code=opus;base64,/,
            ''
          );
          onResult(audioData, getTypeRecording(), blobUrl).then( () => {
            setLoadingTranslate(false);
          })
          .catch(e => {
            console.log(e);
            onStop();
            setLoadingTranslate(false);
            setShowFeedback(true);
          });
          chunks = [];
        };
        setMediaRecorder(null);
      };
      if (mediaRecorder.state === 'inactive') mediaRecorder.start();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [mediaRecorder]);


  const onSpeechRecognitionChange = useCallback(() => {

    function onSilence(){
        recognition.stop();
    }
    function onSpeak(){
      // try{ // calling it twice will throw...
      //   recognition.start();
      // }
      // catch(e){}
    } 

    if (recognition !== null) {
      navigator.mediaDevices.getUserMedia({audio:true})
      .then(stream => detectSilence(stream, onSilence, onSpeak))
      .catch(e => console.log(e.message));
    }
      
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [recognition]);

  useEffect(onMediaRecorderChange, [mediaRecorder, onMediaRecorderChange]);
  // useEffect(onSpeechRecognitionChange, [recognition, onSpeechRecognitionChange]);

  
  useEffect(() => {
    setIconAudio(<FaMicrophone id="microfone-icon" />);
    if (loadingTranslate) {
      setIconAudio(<Loader type="Puff" color="black" height={80} width={80} />);
    } else {
      setIconAudio(<FaMicrophone id="microfone-icon" />);
    }

    if (gravando) {
      setIconAudio(<FaStop id="microfone-icon" />);
    }
  }, [loadingTranslate, gravando]);

  const getTypeRecording = ()=> {
    var sUsrAg = window.navigator.userAgent.toLowerCase();

    let type = '';

    if (sUsrAg.indexOf("edge") > -1) {//MS Edge (EdgeHtml)
      type = 'mediaRecorder';
    } else if(sUsrAg.indexOf("edg") > -1){//MS Edge Chromium
      type = 'mediaRecorder';
    } else if (sUsrAg.indexOf("opr") > -1) {//opera
      type = 'mediaRecorder';
    } else if(sUsrAg.indexOf("chrome") > -1) {
      type = 'speechRecognition';
    } else if(sUsrAg.indexOf("trident") > -1){//Internet Explorer
      type = 'mediaRecorder';
    } else if (sUsrAg.indexOf("firefox") > -1) {
      type = 'mediaRecorder';
    } else if (sUsrAg.indexOf("safari") > -1) {
      type = 'mediaRecorder';
    } else{
      type = 'mediaRecorder';
    } 

    return type;    
  }
  
  useEffect(()=>{
    let type = getTypeRecording();

    if(type === 'speechRecognition')
      setRecognition(createRecognition());

    setUseSpeechRecognitionOrMediaRecorder(type);

    // eslint-disable-next-line react-hooks/exhaustive-deps
  },[]);

  return (
    <Container>
        <Mic
          onClick={
            useSpeechRecognitionOrMediaRecorder === 'mediaRecorder' ?
              mediaRecorderClickGravar
            : useSpeechRecognitionOrMediaRecorder === 'speechRecognition' ?
              recognitionClickGravar
            : mediaRecorderClickGravar
          }
          style={style}
          className="secondary"
          id="microfone-id"
          disabled={disable}
        >
          {iconAudio}
        </Mic>
       <Alert show={showAlert} className="row">
        <div className="col">
          <div className="alert alert-danger alert-dismissible fade show">
            <h2
              style={{ fontSize: 16, fontFamily: 'Nunito' }}
              className="text-primary"
            >
              Esse navegador não suporta gravação!
            </h2>
            <span style={{ fontSize: 13, fontWeight: 400, color: '#4A4A4A', margin: 0 }}>
              Utilize outro navegador.
            </span>
            <button className="close" onClick={()=>{setShowAlert(false);}}>&times;</button>
          </div>
        </div>
      </Alert>
    </Container>
  );
}

export default Microfone;
