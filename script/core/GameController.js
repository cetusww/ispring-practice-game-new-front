import {World} from '../ecs/ECS';
import {BackgroundSystem} from '../systems/BackgroundSystem.js';
import {Loader} from './Loader';
import {StageManager} from '../utils/StageManager.js';
import level1Data from '../../gamedata/levels/level1.json';
import * as PIXI from 'pixijs';

export class GameController {
    static async start() {
        let levelData = level1Data;

        const app = new PIXI.Application({
            background: '#000000',
            width: window.innerWidth,
            height: window.innerHeight
        });
        document.body.appendChild(app.view);

        StageManager.initialize(app);

        const world = new World();
        world.addSystem(new BackgroundSystem());

        try {
            await Loader.loadLevel(levelData, world,);
        } catch (error) {
            console.error('Error loading level:', error);
            return;
        }

        app.ticker.add((delta) => {
            world.update(delta);
        });
    }
}