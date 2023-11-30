import React, { useState, useCallback, useRef } from 'react';
import { Player, Wrapper } from '../styles';
import { FaVolumeUp, FaVolumeOff } from 'react-icons/fa';
import AudioPlayer from 'react-h5-audio-player';
import './styles.css';
import 'react-h5-audio-player/lib/styles.css';
import { MdPlayArrow, MdPause } from 'react-icons/md';

function audio({url, questaoId}) {
  const [playing, setPlaying] = useState(false);
  const audioRef = useRef();

  const playAudio = useCallback(() => {
    if (!playing) {
      audioRef.current.play();
    }
  }, [playing]);

  const stopAudio = useCallback(() => {
    if (playing) {
      audioRef.current.pause();
      // audioRef.current.currentTime = 0;
      setPlaying(false);
    }
  }, [playing]);

  const onStop = useCallback(() => {
    setPlaying(false);
  }, []);

  const onPlay = useCallback(() => {
    setPlaying(true);
  }, []);

  return (
    <Wrapper
      style={{
        display: (!url || url === undefined || url === '') && 'none'
      }}
    >
      {url && url !== undefined && url !== '' &&
        <AudioPlayer
          autoPlay={false}
          src={url}
          className="border-radius audio-player"
          layout={'horizontal-reverse'}
          customControlsSection={['MAIN_CONTROLS']}
          showJumpControls={false}
          customIcons={{
            play: <MdPlayArrow />,
            pause: <MdPause />
          }}
        />
      }
    </Wrapper>
    // <Player
    //   onClick={!playing ? playAudio : stopAudio}
    // >
    //   <div className="d-flex align-items-center text-center" style={{height: 75}}>
    //     <FaVolumeUp
    //       style={{
    //         marginRight: 5,
    //         opacity: playing ? 1 : 0
    //       }}
    //       color={'#4A4A4A'}
    //     />
    //     <span style={{ fontSize: 50, color: '#4A4A4A' }}>
    //       Áudio
    //     </span>
    //   </div>
    //   <audio
    //     id={questaoId}
    //     style={{ display: 'none' }}
    //     src={url}
    //     onPlay={onPlay}
    //     onEnded={onStop}
    //     className="listen"
    //     ref={audioRef}
    //   ></audio>
    // </Player>
  );
}

export default audio;