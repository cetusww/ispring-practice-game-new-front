export class LevelParser {
    static async parse(levelPath) {
        const response = await fetch(levelPath);
        if (!response.ok) {
            throw new Error(`Failed to load level: ${response.statusText}`);
        }
        return await response.json();
    }
}