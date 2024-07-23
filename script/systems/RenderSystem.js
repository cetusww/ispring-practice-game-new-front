import { System } from '../ecs/ECS.js';

export class RenderSystem extends System {
    constructor(world) {
        super();
    }

    update(delta) {
        this.entities.forEach(entity => {
        });
    }
}