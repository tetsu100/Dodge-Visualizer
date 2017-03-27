
myHexValues = {}
files = dir('*.jpeg');
for i = 1:numel(files)
Image = imread(files(i).name);
redChannel = Image(:, :, 1);
greenChannel = Image(:, :, 2);
blueChannel = Image(:, :, 3);


redMean = mean2(redChannel);
greenMean = mean2(greenChannel);
blueMean = mean2(blueChannel);


myHex = rgb2hex([redMean, greenMean, blueMean])


myHexValues{end+1} = myHex
end
results = string(myHexValues);


fid = fopen('results.txt','w');

fprintf(fid,'%s\n',results);

fclose(fid);