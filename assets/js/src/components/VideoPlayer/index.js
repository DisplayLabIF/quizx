import React, { useCallback, useRef, useState, useEffect } from 'react';
import ReactPlayer from 'react-player';
import { Container, Controls } from './styles';
import Slider from '@material-ui/core/Slider';
import { withStyles } from '@material-ui/core/styles';
import { FaExpandAlt, FaPause, FaPlay } from 'react-icons/fa';
import screenfull from 'screenfull';

const PlayerSlider = withStyles({
  root: {
    color: '#000000',
    height: 2,
    marginLeft: 5
  },
  thumb: {
    height: 16,
    width: 16,
    backgroundColor: '#000000',
    marginTop: -7,
    marginLeft: -12
  },
  active: {},
  valueLabel: {
    left: 'calc(-50% + 4px)'
  },
  track: {
    height: 2,
    borderRadius: 2
  },
  rail: {
    height: 2,
    borderRadius: 2
  }
})(Slider);

export default function VideoPlayer(props) {
  const playerRef = useRef(null);
  const [playedSeconds, setPlayedSeconds] = useState(0);
  const [duration, setDuration] = useState(0);
  const [showControls, setShowControls] = useState(true);
  const [playing, setPlaying] = useState(false);

  function onVideoProgress(data) {
    setPlayedSeconds(data.playedSeconds);
  }

  function onReady(player) {
    setDuration(player.getDuration());
  }

  function handleChangeSlider(e, newValue) {
    setPlayedSeconds(newValue);
    if (playerRef) {
      playerRef.current.seekTo(newValue, 'seconds');
    }
  }

  function secondsToDate(seconds) {
    return new Date(seconds * 1000).toISOString().substr(14, 5);
  }

  const handleMouseOverControls = useCallback(e => {
    setShowControls(true);
  }, []);

  /*const handleMouseOutControls = useCallback(e => {
    setShowControls(false);
  }, []);*/

  const playVideo = useCallback(() => {
    setPlaying(value => !value);
  }, []);

  const handlePause = useCallback(() => {
    setPlaying(false);
    setShowControls(true);
  }, []);

  useEffect(() => {
    if (showControls && playing) {
      setTimeout(() => {
        if (playing) setShowControls(false);
      }, 1500);
    }

    if (!playing) setShowControls(true);
  }, [showControls, playing]);

  function openFullscreen() {
    if (screenfull.isEnabled) {
      screenfull.request(playerRef.current.wrapper);
    }
  }

  return (
    <Container
      style={{
        ...props.style,
        width: props.width ? props.width : 400,
        height: props.height ? props.height : 225
      }}
    >
      <ReactPlayer
        ref={playerRef}
        url={props.url}
        width={props.width ? props.width : 400}
        height={props.height ? props.height : 225}
        muted={props.muted ? props.muted : false}
        playing={props.playing ? props.playing : playing}
        controls={false}
        config={props.config ? props.config : {}}
        light={props.light ? props.light : false}
        style={props.style ? props.style : {}}
        onProgress={onVideoProgress}
        onReady={onReady}
        onPause={handlePause}
        onMouseOver={handleMouseOverControls}
        onMouseMove={handleMouseOverControls}
        onClick={props.controls ? playVideo : () => {}}
        stopOnUnmount
      />
      {props.controls && (
        <Controls
          className="border-radius primary"
          showControls={showControls}
          onMouseOver={handleMouseOverControls}
          onMouseMove={handleMouseOverControls}
        >
          <button className="btn-play" onClick={playVideo}>
            {!playing ? <FaPlay /> : <FaPause />}
          </button>
          <PlayerSlider
            value={playedSeconds}
            max={duration}
            onChange={handleChangeSlider}
            aria-labelledby="continuous-slider"
            style={{ width: '50%' }}
          />
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'flex-end',
              height: '100%',
              maxWidth: '30%',
              marginRight: '2%'
            }}
          >
            <span
              style={{
                fontSize: 15,
                width: 95,
                textAlign: 'center',
                color: '#000000'
              }}
            >
              {secondsToDate(playedSeconds)} / {secondsToDate(duration)}
            </span>
            <button className="btn-expand" onClick={openFullscreen}>
              <FaExpandAlt />
            </button>
          </div>
        </Controls>
      )}
    </Container>
  );
}
